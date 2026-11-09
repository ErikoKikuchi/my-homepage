<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Enums\Pilates\ReservationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pilates\Admin\StoreAdminReservationRequest;
use App\Http\Requests\Pilates\Admin\UpdateAdminReservationRequest;
use App\Models\Pilates\Client;
use App\Models\Pilates\LessonSlot;
use App\Models\Pilates\LessonTemplate;
use App\Models\Pilates\Location;
use App\Models\Pilates\Reservation;
use App\Services\Pilates\PhoneNumberNormalizerService;
use App\Services\Pilates\ReservationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ReservationController extends Controller
{
    public function __construct(
        private ReservationService $reservationService,
        private PhoneNumberNormalizerService $phoneNormalizer,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(?LessonSlot $lessonSlot = null)
    {
        $query = Reservation::query()
            ->when($lessonSlot, fn ($query) => $query->where('lesson_slot_id', $lessonSlot->id))
            ->where(fn ($query) => $query->past()->orWhereIn('status', [
                ReservationStatus::Canceled,
                ReservationStatus::NoShow,
                ReservationStatus::Rescheduled,
            ]))
            ->orderByDesc(
                LessonSlot::withTrashed()->select('date')
                    ->whereColumn('lesson_slots.id', 'reservations.lesson_slot_id')
            )
            ->latest();

        return Inertia::render('Admin/Pilates/Reservations/Index', [
            'reservations' => $this->historyPage($query),
        ]);
    }

    public function cancellations()
    {
        $query = Reservation::query()
            ->where('status', ReservationStatus::Canceled)
            ->orderByDesc('cancelled_at')
            ->latest();

        return Inertia::render('Admin/Pilates/Analytics/Reservations/Index', [
            'reservations' => $this->historyPage($query),
        ]);
    }

    private function historyPage(Builder $query): LengthAwarePaginator
    {
        return $query
            ->with([
                'client:id,name',
                'location:id,name',
                'lessonSlot.lessonTemplate',
                'rescheduledFrom.lessonSlot.lessonTemplate',
                'rescheduledTo.lessonSlot.lessonTemplate',
            ])
            ->orderByDesc('id')
            ->paginate(20)
            ->through(fn (Reservation $reservation) => [
                'id' => $reservation->id,
                'clientName' => $reservation->client?->name,
                'scheduledAt' => $this->historyDateTime($reservation->lessonSlot),
                'locationName' => $reservation->location?->name,
                'status' => $reservation->status->label(),
                'cancelledAt' => $reservation->cancelled_at?->timezone('Asia/Tokyo')->format('Y/m/d H:i'),
                'cancelledBy' => $reservation->cancelled_by,
                'rescheduledFrom' => $this->historyDateTime($reservation->rescheduledFrom?->lessonSlot),
                'rescheduledTo' => $this->historyDateTime($reservation->rescheduledTo?->lessonSlot),
            ]);
    }

    private function historyDateTime(?LessonSlot $lessonSlot): ?string
    {
        if ($lessonSlot === null) {
            return null;
        }

        return $lessonSlot->date->format('Y/m/d').' '.$lessonSlot->lessonTemplate->start_time;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(LessonSlot $lessonSlot)
    {
        return Inertia::render('Admin/Pilates/Reservations/Create', [
            'lessonSlot' => $lessonSlot->load('lessonTemplate'),
        ]);
    }

    public function store(StoreAdminReservationRequest $request, LessonSlot $lessonSlot)
    {
        $data = $request->validated();

        try {
            DB::connection('client_db')->transaction(function () use ($data, $lessonSlot) {
                $slot = LessonSlot::query()->lockForUpdate()->findOrFail($lessonSlot->id);
                $client = empty($data['client_id'])
                    ? Client::create([
                        'name' => $data['name'],
                        'is_active' => false,
                        'is_provisional' => true,
                        'phone' => $this->phoneNormalizer->normalize($data['phone'] ?? null),
                    ])
                    : Client::query()->lockForUpdate()->findOrFail($data['client_id']);

                if (! empty($data['relationship_note'])) {
                    $prefix = $client->relationship_note ? $client->relationship_note."\n" : '';
                    $client->update(['relationship_note' => $prefix.$data['relationship_note']]);
                }

                $this->reservationService->createReservation($slot, $client, ['note' => $data['note'] ?? null]);
            });
        } catch (HttpExceptionInterface $exception) {
            if ($exception->getStatusCode() !== 409) {
                throw $exception;
            }

            return back()->withInput()->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('pilates.admin.calendar')
            ->with('message', '予約を登録しました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        $reservation->load('lessonSlot.lessonTemplate', 'lessonSlot.location', 'location:id,name', 'client:id,name');

        $lessonTemplates = LessonTemplate::where('is_active', true)->orderBy('start_time')->get();
        $locations = Location::where('is_active', true)->orderBy('name')->get();

        return Inertia::render('Admin/Pilates/Reservations/Edit', compact('reservation', 'lessonTemplates', 'locations'));
    }

    public function update(UpdateAdminReservationRequest $request, Reservation $reservation)
    {
        $data = $request->validated();

        $lessonTemplate = LessonTemplate::findOrFail($data['lesson_template_id']);
        $locationId = $data['location_id'] ?? null;
        $location = $locationId ? Location::findOrFail($locationId) : null;

        try {
            $this->reservationService->rescheduleReservation(
                $reservation,
                $data['date'],
                $lessonTemplate,
                $location
            );
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('pilates.admin.calendar')
            ->with('message', '予約を変更しました。');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->update([
            'status' => ReservationStatus::Canceled,
            'cancelled_at' => now(),
            'cancelled_by' => 'admin',
        ]);

        return redirect()
            ->route('pilates.admin.calendar')
            ->with('message', '予約をキャンセルしました。');
    }
}
