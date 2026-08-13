<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pilates\Admin\StoreAdminReservationRequest;
use App\Models\Pilates\LessonSlot;
use App\Models\Pilates\Reservation;
use App\Models\Pilates\LessonTemplate;
use App\Models\Pilates\Location;
use App\Models\Auth\User;
use App\Services\Pilates\ReservationService;
use App\Services\Pilates\UserProvisioningService;
use App\Enums\Pilates\ReservationStatus;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Pilates\Admin\UpdateAdminReservationRequest;

class ReservationController extends Controller
{   
    public function __construct(
    private ReservationService $reservationService,
    private UserProvisioningService $userProvisioningService,
) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.pilates.admin.reservations.index');
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(LessonSlot $lessonSlot)
    {
        return view('pages.pilates.admin.reservations.create', [
            'lessonSlot' => $lessonSlot,
        ]);
    }

    public function store(StoreAdminReservationRequest $request, LessonSlot $lessonSlot)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $lessonSlot) {
            $alreadyReserved = $lessonSlot->reservations()
                ->where('status', '!=', ReservationStatus::Canceled)
                ->exists();

            if ($alreadyReserved) {
                throw new \Exception('このスロットはすでに予約済みです');
            }

            $user = !empty($data['user_id'])
            ? User::findOrFail($data['user_id'])
            : $this->userProvisioningService->create(
                $data['name'],
                $data['phone'] ?? null,
            );
            if (!empty($data['relationship_note'])) {
                $prefix = $user->relationship_note ? $user->relationship_note . "\n" : '';
                $user->update([
                    'relationship_note' => $prefix . $data['relationship_note'],
                ]);
            }

            $this->reservationService->createReservation($lessonSlot, [
                'user_id' => $user->id,
                'participants' => $data['participants'],
                'participants_name' => $data['participants_name'] ?? null,
                'participants_phone' => $data['participants_phone'] ?? null,
                'note' => $data['note'] ?? null,
            ]);
        });

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
        $reservation->load('lessonSlot.lessonTemplate', 'lessonSlot.location');

        $lessonTemplates = LessonTemplate::where('is_active', true)->orderBy('start_time')->get();
        $locations = Location::where('is_active', true)->orderBy('name')->get();

        return view('pages.pilates.admin.reservations.edit', compact('reservation', 'lessonTemplates', 'locations'));
    }

    public function update(UpdateAdminReservationRequest $request, Reservation $reservation)
    {
        $data = $request->validated();

        $lessonTemplate = LessonTemplate::findOrFail($data['lesson_template_id']);
        $location = $data['location_id'] ? Location::findOrFail($data['location_id']) : null;

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
            'status'       => ReservationStatus::Canceled,
            'cancelled_at' => now(),
            'cancelled_by' => 'admin',
        ]);

        return redirect()
            ->route('pilates.admin.calendar')
            ->with('message', '予約をキャンセルしました。');
    }
}