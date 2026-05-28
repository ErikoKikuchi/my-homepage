@extends('layouts.app')

@section('css')
    @if(!app()->environment(['testing']) && !config('app.vite_disabled'))
        @vite('resources/js/users/forgot-password.js')
    @endif
@endsection

@section('content')
    <div class="container">
        <div class="forgot-password">
            <div class="forgot-password__message">
                <p class="forgot-password__main-sentence">下記の欄にメールアドレスを入力し、メール内のリンクよりパスワードを再登録してください。</p>
            </div>
            <form class="forgot-password__form" method="POST" action="{{ route('password.email') }}">
                @csrf
                    <input class="forgot-password__form--email" type="email" name="email" />
                    <button class="forgot-password__form--submit" type="submit">送信</button>
            </form>
        </div>
    </div>
@endsection