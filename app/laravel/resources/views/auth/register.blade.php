@extends ('layouts.app')

@section ('header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        新規登録
    </h1>
@endsection

@section ('content')
    <form class="max-w-md mx-auto" action="/register" method="post">
        @csrf
        <div class="p-5">
            <label class="text-sm/1.8" for="name">名前 </label>
            <input
                class="border border-forest w-full h-10"
                type="text"
                id="name"
                name="name"
                value="{{old('name')}}"
            />
            <div class="alert">
                @foreach ($errors->get('name') as $message)
                    <p>{{$message}}</p>
                @endforeach
            </div>
        </div>
        <div class="p-5">
            <label class="text-sm/1.8" for="email">メールアドレス</label>
            <input
                class="border border-forest w-full h-10"
                type="email"
                id="email"
                name="email"
                value="{{old('email')}}"
            />
            <div class="alert">
                @foreach ($errors->get('email') as $message)
                    <p class="error-message">{{$message}}</p>
                @endforeach
            </div>
        </div>
        <div class="p-5">
            <label class="text-sm/1.8" for="password">パスワード</label>
            <input
                class="border border-forest w-full h-10"
                type="password"
                id="password"
                name="password"
            />
            <div class="alert">
                @error ('password')
                    <p class="error-message">{{$message}}</p>
                @enderror
            </div>
        </div>
        <div class="p-5">
            <label class="text-sm/1.8" for="password">パスワード確認</label>
            <input
                class="border border-forest w-full h-10"
                type="password"
                id="password_confirmation"
                name="password_confirmation"
            />
            <div class="alert">
                @error ('password_confirmation')
                    <p class="error-message">{{$message}}</p>
                @enderror
            </div>
        </div>
        <div class="p-5">
            <label class="text-sm/1.8" for="password">医療従事者ですか？</label>
            <div>
                <label>
                    <input type="radio" name="is_medical" value="1" /> はい
                </label>
                <label>
                    <input type="radio" name="is_medical" value="0" />
                    いいえ
                </label>
            </div>
            <div class="alert">
                @error ('is_medical')
                    <p class="error-message">{{$message}}</p>
                @enderror
            </div>
        </div>
        <div class="flex justify-center m-6">
            <button class="btn btn-primary" type="submit">登録する</button>
        </div>
    </form>
    <div class="flex flex-col items-center gap-4 my-6">
        <a
            class="text-xs text-forest cursor-pointer hover:text-forest-dark"
            href="/login"
            >ログインはこちら</a
        >
    </div>
@endsection
