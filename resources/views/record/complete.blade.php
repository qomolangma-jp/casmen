@extends('layouts.user')

@section('title', 'CASMEN｜らくらくセルフ面接')

@section('content')
<header class="header">
    <div class="header__container">
        <img src="{{ asset('assets/user/img/logo2.png') }}" alt="らくらくセルフ面接">
    </div>
</header>

<main class="main">
    <div class="main__container">
        <div class="instruction instruction__interview bg-frame">
            <div class="instruction__confirm-inner">
                <p class="instruction__complete-message">セルフ面接お疲れさまでした。<br>店舗からのご連絡をお待ち<br>ください。</p>
                <div class="instruction__complete-character-message">
                    <div class="instruction__complete-bubble">
                        <img src="{{ asset('assets/user/img/bubble.png') }}" class="instruction__bubble-img" alt="吹き出し">
                        <img src="{{ asset('assets/user/img/great-job.png') }}" class="instruction__bubble-text" alt="バッチリ決まってたよ！">
                    </div>
                    <div class="instruction__complete-character">
                        <img src="{{ asset('assets/user/img/bear-lg.png') }}" class="instruction__bear-img" alt="クマのキャラクター">
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<footer class="footer">
    <div class="footer__container">
        <p>ご不明点やトラブルがあれば、下記のサポートまでお気軽にご連絡ください。</p>
        <a href="mailto:support@casmen.jp">support@casmen.jp</a>
    </div>
</footer>
@endsection
