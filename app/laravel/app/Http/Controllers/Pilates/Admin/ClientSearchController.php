<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Services\Pilates\PhoneNumberNormalizerService;
use Illuminate\Http\Request;

class ClientSearchController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->query('q');
        if (!$q) {
            return collect();
        }

        $isPhoneLike = preg_match('/^[0-9\-]+$/', $q);

        $users = User::when($isPhoneLike, function ($query) use ($q) {
                $normalizedPhone = app(PhoneNumberNormalizerService::class)->normalize($q);
                $query->where('phone', $normalizedPhone);
            }, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('name_kana', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('is_client')
            ->limit(10)
            ->get(['id', 'name', 'phone', 'relationship_note', 'is_client']);
        
        return response()->json($users);
    }
}
