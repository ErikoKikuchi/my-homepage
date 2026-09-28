<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;  



class UserRegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $credentials = $request->validated();
        $user = User::create([
            'name' => $credentials['name'],
            'email'=> $credentials['email'],
            'password'=> Hash::make($credentials['password']),
            'is_pilates_user'=> $credentials['service'] === 'pilates',
            'is_medical'=>$credentials['service'] === 'thinkmotion',
            'profile_completed' => false,
            'bookshelf_public'  => true,
            'is_client'         => false,
            ]);


        //メール認証
        event(new Registered($user));
        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return response()->json([
            'redirectTo' => '/auth/email/verify',
        ]);
    }
}
