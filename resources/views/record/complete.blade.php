@extends('layouts.user')

@section('title', 'CASMEN｜らくらくセルフ面接')

@section('content')
<header>
    <div class="header-container">
        <div class="header-container-inner line-logo">
            <img src="{{ asset('assets/user/img/logo2.png') }}" alt="らくらくセルフ面接">
        </div>
    </div>
</header>
<main>
    <div class="main-container">
        <div class="main-content complete-content">
            <div class="complete-description">
                <p class="done-message">セルフ面接お疲れさまでした。<br>店舗からのご連絡をお待ち<br>ください。</p>
            </div>
            <div class="lg-video complete-character">
                <div class="character-short">
                    <div class="bubble-lg">
                        <img src="{{ asset('assets/user/img/great-job.png') }}" class="well-done" alt="バッチリ決まってたよ！">
                    </div>
                    <img src="{{ asset('assets/user/img/bear.png') }}" alt="クマのキャラクター">
                </div>
            </div>
        </div>
        <button id="close-btn" type="button" class="purple-btn" onclick="window.close()">閉じる</button>
    </div>
</main>
<footer>
    <div class="footer-container">
        <p>ご不明点やトラブルがあれば、下記のサポートまでお気軽にご連絡ください。</p>
        <a href="mailto:support@casmen.jp">support@casmen.jp</a>
    </div>
</footer>
@endsection
