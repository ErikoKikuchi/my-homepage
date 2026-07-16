<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pilates\Admin\StoreLocationRequest;
use App\Http\Requests\Pilates\Admin\UpdateLocationRequest;
use App\Models\Pilates\Location;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.pilates.admin.locations-index', compact('locations'));
    }

    public function create()
    {
        return view('pages.pilates.admin.locations-create');
    }

    public function store(StoreLocationRequest $request)
    {
        Location::create($request->validated());

        return redirect()
            ->route('pilates.admin.location.index')
            ->with('message', '場所を登録しました。');
    }

    public function edit(Location $location)
    {
        return view('pages.pilates.admin.locations-edit', compact('location'));
    }

    public function update(UpdateLocationRequest $request, Location $location)
    {
        $location->update($request->validated());

        return redirect()
            ->route('pilates.admin.location.index')
            ->with('message', '場所情報を更新しました。');
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()
            ->route('pilates.admin.location.index')
            ->with('message', '場所を削除しました。');
    }

    public function show()
    {
        $locations = Location::where('is_active', false)
            ->orderBy('name')
            ->get();

        return view('pages.pilates.admin.locations-archive', compact('locations'));
    }

    public function archive(Location $location)
    {
        $location->update(['is_active' => false]);
        return redirect()
            ->route('pilates.admin.location.index')
            ->with('message', '場所をアーカイブしました。');
    }
    public function restore(Location $location)
    {
        $location->update(['is_active' => true]);

        return redirect()
            ->route('pilates.admin.location.index')
            ->with('message', '場所を有効化しました。');
    }
}