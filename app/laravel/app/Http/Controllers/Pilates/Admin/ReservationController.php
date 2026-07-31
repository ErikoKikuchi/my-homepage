<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pilates\Admin\StoreAdminReservationRequest;
use App\Models\Pilates\LessonSlot;
use App\Models\Pilates\Reservation;
use App\Services\Pilates\ReservationService;
use App\Services\Pilates\UserProvisioningService;
use App\Enums\Pilates\ReservationStatus;
use Illuminate\Support\Facades\DB;

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

            $user = $this->userProvisioningService->create(
                $data['name'],
                $data['phone'] ?? null,
            );

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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
}
