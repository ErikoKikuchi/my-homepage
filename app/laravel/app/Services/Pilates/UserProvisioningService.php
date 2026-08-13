<?php

namespace App\Services\Pilates;

use App\Models\Auth\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class UserProvisioningService
{
    public function searchByName(string $keyword): Collection
    {
        return User::where('name', 'like', "%{$keyword}%")
            ->limit(10)
            ->get(['id', 'name', 'phone']);
    }

    public function create(string $name, ?string $phone): User
    {
        return User::create([
            'name' => $name,
            'phone' => $phone,
            'email' => null,
            'password' => Hash::make(Str::random(32)),
            'is_pilates_user' => true,
            'is_medical' => false,
            'profile_completed' => false,
            'bookshelf_public' => false,
            'is_client' => false,
        ]);
    }
}