<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Auth\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserRegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $credentials = $request->validated();

        $user = DB::connection('mysql')->transaction(
            function () use ($credentials) {
                $user = User::create([
                    'email' => $credentials['email'],
                    'password' => Hash::make($credentials['password']),
                    'is_pilates_user' => $credentials['service'] === 'pilates',
                    'is_medical' => $credentials['service'] === 'thinkmotion',
                    'profile_completed' => false,
                    'bookshelf_public' => true,
                    'is_client' => false,
                ]);

        if ($user->is_pilates_user) {
            $client = $user->client()->make([
                'name' => $credentials['name'],
            ]);
            $client->setConnection('mysql');
            $client->save();
        } elseif ($user->is_medical) {
            $user->profile()->create([
                'display_name' => $credentials['name'],
            ]);
        }
        return $user;
            }
        );


        // メール認証
        event(new Registered($user));
        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return response()->json([
            'redirectTo' => '/auth/email/verify',
        ]);
    }
}
