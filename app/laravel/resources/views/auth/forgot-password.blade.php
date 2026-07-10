@extends ('layouts.app')

@section ('header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        パスワード再設定
    </h1>
@endsection

@section ('content')
    <div class="max-w-md mx-auto">
        <p class="text-sm/1.8">下記の欄にメールアドレスを入力し、<br />メール内のリンクよりパスワードを再登録してください。</p>
    </div>
    <form
        class="max-w-md mx-auto"
        method="POST"
        action="{{ route('password.email') }}"
    >
        @csrf
        <input
            class="border border-forest w-full h-10"
            type="email"
            name="email"
        />
        <div class="alert">
            @error ('email')
                <p>{{$message}}</p>
            @enderror
        </div>
        <div class="flex justify-center m-6">
            <button class="btn btn-primary" type="submit">送信</button>
        </div>
    </form>
@endsection
