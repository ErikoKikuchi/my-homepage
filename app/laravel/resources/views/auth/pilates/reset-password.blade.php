@extends ('layouts.app')

@section ('header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        新しいパスワードの設定
    </h1>
@endsection

@section ('content')
    <div class="max-w-md mx-auto">
        <p class="text-sm/1.8">新しいパスワードを入力してください。</p>
        <form class="p-5" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input
                type="hidden"
                name="token"
                value="{{ request()->route('token') }}"
            />
            <input type="hidden" name="email" value="{{ request()->email }}" />
            <label class="text-sm/1.8" for="password">パスワード </label>
            <input
                class="border border-forest w-full h-10"
                type="password"
                name="password"
            />
            <div class="alert">
                @error ('password')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <label class="text-sm/1.8" for="password_confirmation"
                >確認用パスワード
            </label>
            <input
                class="border border-forest w-full h-10"
                type="password"
                name="password_confirmation"
            />
            <div class="alert">
                @error ('password_confirmation')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div class="flex justify-center m-6">
                <button class="btn btn-primary" type="submit">送信</button>
            </div>
        </form>
    </div>
@endsection
