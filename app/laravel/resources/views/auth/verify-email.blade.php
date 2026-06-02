@extends ('layouts.app')

@section ('header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        メール認証
    </h1>
@endsection

@section ('content')
    <div class="max-w-md mx-auto">
        <div class="p-5">
            <p class="text-sm/1.8">登録していただいたメールアドレスに認証メールを送付しました。</p>
            <p class="text-sm/1.8">メール内認証を完了してください。</p>
        </div>
        <form
            class="flex justify-center m-6"
            method="GET"
            action="{{ route('verification.open') }}"
        >
            <button class="btn btn-outline" type="submit">
                認証はこちらから
            </button>
        </form>
        <form
            class="flex justify-center m-6"
            method="POST"
            action="{{ route('verification.send') }}"
        >
            @csrf
            <button
                class="text-xs text-forest cursor-pointer hover:text-forest-dark"
                type="submit"
            >
                認証メールを再送する
            </button>
        </form>
    </div>
@endsection
