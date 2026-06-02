@extends ('layouts.app')

@section ('header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        ログイン
    </h1>
@endsection

@section ('content')
    <form class="max-w-md mx-auto" action="/admin/login" method="post">
        @csrf
        <div class="p-5">
            <label class="text-sm/1.8" for="email">メールアドレス </label>
            <input
                class="border border-forest w-full h-10"
                type="email"
                id="email"
                name="email"
                value="{{old('email')}}"
            />
            <div class="alert">
                @foreach ($errors->get('email') as $message)
                    <p>{{$message}}</p>
                @endforeach
            </div>
        </div>
        <div class="p-5">
            <label class="text-sm/1.8" for="password">パスワード </label>
            <input
                class="border border-forest w-full h-10"
                type="password"
                id="password"
                name="password"
            />
            <div class="alert">
                @error ('password')
                    <p>{{$message}}</p>
                @enderror
            </div>
        </div>
        <div class="flex justify-center m-6">
            <button class="btn btn-primary" type="submit">ログインする</button>
        </div>
    </form>
@endsection
