@extends ('layouts.app')

@section ('header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        二要素認証
    </h1>
@endsection

@section ('content')
    <div class="max-w-md mx-auto">
        <form class="p-5" action="/admin/two-factor/verify" method="post">
            @csrf
            <label class="text-sm/1.8" for="two_factor_secret"
                >認証コード（6桁）</label
            >
            <input
                class="border border-forest w-full h-10"
                type="text"
                id="two_factor_secret"
                name="two_factor_secret"
                inputmode="numeric"
                maxlength="6"
                autocomplete="one-time-code"
            />
            <div class="alert">
                @foreach ($errors->get('two_factor_secret') as $message)
                    <p class="error-message">{{ $message }}</p>
                @endforeach
            </div>
            <div class="flex justify-center m-6">
                <button class="btn btn-primary" type="submit">認証する</button>
            </div>
        </form>
    </div>
@endsection
