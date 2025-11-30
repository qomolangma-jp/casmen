@extends('layouts.admin-auth')

@section('title', 'CASMEN｜管理画面ログイン')

@push('scripts')
<script src="{{ asset('assets/admin/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/main.js') }}"></script>
@endpush

@section('content')
<header>
    <div class="header-container">
        <img src="{{ asset('assets/admin/img/logo_casmen_blue.png') }}" alt="Casmenロゴ">
    </div>
</header>
<main>
    <div class="login-container">
        <h1>管理画面ログイン</h1>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            @if ($errors->any())
            <div class="warning">
                <span>エラー</span>
                <ul class="warning-list">
                    @foreach ($errors->all() as $error)
                        <li>・{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="form-item email">
                <span>
                    <img src="{{ asset('assets/admin/img/email-icon.png') }}" alt="メールアイコン">
                </span>
                <input id="email" name="email" placeholder="メールアドレス" type="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-item password">
                <span>
                    <img src="{{ asset('assets/admin/img/password-icon.png') }}" alt="鍵アイコン">
                </span>
                <input id="password" name="password" placeholder="パスワード" type="password" required>
                <div class="visible-icons">
                    <img src="{{ asset('assets/admin/img/invisible-icon.png') }}" class="invisible" alt="非表示アイコン">
                    <img src="{{ asset('assets/admin/img/visible-icon.png') }}" class="visible" alt="表示アイコン">
                </div>
            </div>
            <div class="browser">
                <div class="recommended-browser">
                    <span>推奨ブラウザ:</span>
                    <span>Chrome</span>
                </div>
                <img src="{{ asset('assets/admin/img/chrome.png') }}" alt="Chromeロゴ">
            </div>
            <button id="login" type="submit" class="login-btn">Login</button>
        </form>
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">パスワードを忘れた方</a>
        @endif
    </div>
    <div class="privacy-policy">
        <a href="#">個人情報の取り扱いについて</a>
    </div>
</main>
<footer>
    <div class="footer-container">
        <small>Copyright&copy;CASMEN All Rights Reserved.</small>
    </div>
</footer>
@endsection
