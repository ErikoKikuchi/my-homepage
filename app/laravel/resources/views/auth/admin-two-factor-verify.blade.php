@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="login-form">
            <div class="login-form__title">
                <h1 class="form__title">二要素認証</h1>
            </div>
            <form class="login-form__inner" action="/admin/two-factor/verify" method="post">@csrf
                <div class="login-form__email">
                    <div class="login-form__label">
                        <label class="login-form__label-title" for="two_factor_secret">認証コード（6桁）</label>
                    </div>
                    <div class="login-form__input">
                        <input class="login-form__input-box" type="text" id="two_factor_secret" name="two_factor_secret"
                            inputmode="numeric" maxlength="6" autocomplete="one-time-code">
                    </div>
                    <div class="error">
                        @foreach($errors->get('two_factor_secret') as $message)
                            <p class="error-message">{{ $message }}</p>
                        @endforeach
                    </div>
                </div>
                <div class="login-form__button">
                    <button class="login__button" type="submit">認証する</button>
                </div>
            </form>
        </div>
    </div>
@endsection