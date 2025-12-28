@extends('layouts.user')

@section('title', 'CASMEN｜らくらくセルフ面接')

@section('content')
<header class="header header-welcome">
    <div class="header__container header__container-welcome">
        <p>{{ $entry->user->shop_name }}からの<br>セルフ面接のご案内です。</p>
    </div>
</header>
<main class="main">
    <div class="main__container main__container-welcome">
        <div class="welcome">
            <div class="welcome__top-img">
                <img src="{{ asset('assets/user/img/welcome.png') }}" alt="Welcome to">
            </div>

            <div class="welcome__bottom-imgs">
                <div class="welcome__character-text-imgs">
                    <img src="{{ asset('assets/user/img/logo.png') }}" class="welcome__text-img-1" alt="らくらくセルフ面接">
                    <img src="{{ asset('assets/user/img/bear.png') }}" class="welcome__character-img" alt="クマのキャラクター">
                </div>

                <div class="welcome__text-img">
                    <img src="{{ asset('assets/user/img/decription.png') }}" alt="スマホからカンタンな質問に答えるだけ！">
                </div>
            </div>
        </div>

        <div class="point bg-frame">
            <div class="point__inner">
                <h2 class="point__title">POINT</h2>
                <ul class="point__list">
                    <li>面接官と会わないから安心</li>
                    <li>24時間365日いつでも面接可能</li>
                    <li>所要時間はたったの2分</li>
                </ul>

                <p class="point__text">リラックスして、普段のあなたのままで<br>質問に答えてください。「次へ」をタップする<br>と、やり方の説明に進みます。</p>
            </div>
        </div>

        <a href="{{ route('record.howto', ['token' => $token]) }}" class="main__btn">次へ</a>

    </div>
</main>

<footer class="footer">
    <div class="footer__container">
        <p>ご不明点やトラブルがあれば、下記のサポートまで<br>お気軽にご連絡ください。</p>
        <a href="mailto:support@casmen.jp">support@casmen.jp</a>
    </div>
</footer>
@endsection
