@extends('layouts.app')

@section('css')
    @if(!app()->environment(['testing']) && !config('app.vite_disabled'))
        @vite('resources/js/users/reset-password.js')
    @endif
@endsection

@section('content')
    <div class="container">
        <div class="reset-password">
            <div class="reset-password__message">
                <p class="reset-password__main-sentence">パスワードを再登録してください。</p>
            </div>
            <form class="reset-password__form" method="POST" action="{{ route('password.update') }}">
                @csrf
                    <input type="hidden" name="token" value="{{ request()->route('token') }}" />
                    <input type="hidden" name="email" value="{{ request()->email }}" />
                    <div class="reset-password__form">
                        <label class="reset-password__form--label" for="password">パスワード</label>
                        <input class="reset-password__form--input" type="password" name="password" />
                    </div>
                    <div class="reset-password__form">
                        <label class="reset-password__form--label" for="password_confirmation">確認用パスワード</label>
                        <input class="reset-password__form--input" type="password" name="password_confirmation" />
                    </div>
                    <button class="reset-password__form--submit" type="submit">送信</button>
            </form>
        </div>
    </div>
@endsection
