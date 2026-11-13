<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pilates\Admin\SavePilatesExerciseRequest;
use App\Models\Pilates\PilatesExercise;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ExerciseController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Pilates/Exercises/Index', [
            'exercises' => PilatesExercise::orderBy('name')->orderBy('id')
                ->get(['id', 'name', 'supplement', 'precautions', 'photos'])
                ->map(fn (PilatesExercise $exercise) => [
                    ...$exercise->only('id', 'name', 'supplement', 'precautions'),
                    'photos' => array_map(
                        fn ($slot) => isset($exercise->photos[$slot])
                            ? route('pilates.admin.exercises.photo', [$exercise, $slot, 'v' => sha1($exercise->photos[$slot])], false)
                            : null,
                        range(0, 2)
                    ),
                ]),
        ]);
    }

    public function store(SavePilatesExerciseRequest $request)
    {
        $this->save($request, new PilatesExercise);

        return back()->with('success', '種目を登録しました。');
    }

    public function update(SavePilatesExerciseRequest $request, PilatesExercise $exercise)
    {
        $this->save($request, $exercise);

        return back()->with('success', '種目を更新しました。');
    }

    public function photo(PilatesExercise $exercise, int $slot)
    {
        $path = $exercise->photos[$slot] ?? null;
        $disk = Storage::disk('local');
        abort_unless($path && $disk->exists($path), 404);

        return $disk->response($path, null, [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function save(SavePilatesExerciseRequest $request, PilatesExercise $exercise): void
    {
        $disk = Storage::disk('local');
        $uploads = [];

        try {
            foreach ($request->file('photos', []) as $slot => $photo) {
                if ($photo === null) {
                    continue;
                }
                $path = $photo->store('pilates/exercises', 'local');
                if ($path === false) {
                    throw new \RuntimeException('Failed to store exercise photo.');
                }
                $uploads[$slot] = $path;
            }

            $replaced = $exercise->getConnection()->transaction(function () use ($exercise, $request, $uploads) {
                $record = $exercise->exists ? PilatesExercise::lockForUpdate()->findOrFail($exercise->id) : $exercise;
                $previous = $record->photos ?? [];
                $photos = $previous;
                foreach ($request->validated('remove_photos', []) as $slot => $remove) {
                    if ($remove) {
                        unset($photos[$slot]);
                    }
                }
                $photos = array_replace($photos, $uploads);
                $record->fill($request->safe()->only(['name', 'supplement', 'precautions']));
                $record->photos = $photos;
                $record->save();

                return array_values(array_diff($previous, $photos));
            });
        } catch (\Throwable $exception) {
            $disk->delete(array_values($uploads));
            throw $exception;
        }

        $disk->delete($replaced);
    }
}
