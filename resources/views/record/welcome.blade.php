@extends('layouts.user')

@section('title', 'CASMEN｜らくらくセルフ面接')

@section('content')
<header>
    <div class="header-container">
        <div class="header-container-inner">
            <div class="welcome-ribbon">
                <img src="{{ asset('assets/user/img/welcome.png') }}" alt="Welcome to">
            </div>
            <div class="logo-imgs">
                <div class="logo">
                    <img src="{{ asset('assets/user/img/logo.png') }}" alt="らくらくセルフ面接">
                    <img src="{{ asset('assets/user/img/decription.png') }}" alt="スマホからカンタンな質問に答えるだけ！">
                </div>
                <div class="bear">
                    <img src="{{ asset('assets/user/img/bear.png') }}" alt="クマのキャラクター">
                </div>
            </div>
        </div>
    </div>
</header>
<main>
    <div class="main-container">
        <div class="main-content short-content">
            <div class="short-description">
                <h2>POINT</h2>
                <ul class="point">
                    <li>面接官と会わないから安心</li>
                    <li>24時間365日いつでも参加可能</li>
                    <li>所要時間はたったの2分</li>
                </ul>
                <p>リラックスして、普段のあなたのままで<br>質問に答えてください。「次へ」をタップする<br>と、やり方の説明に進みます。</p>
            </div>
        </div>
        <a href="{{ route('record.howto', ['token' => $token]) }}" class="purple-btn">次へ</a>
    </div>
</main>
<footer>
    <div class="footer-container">
        <p>ご不明点やトラブルがあれば、下記のサポートまで<br>お気軽にご連絡ください。</p>
        <a href="mailto:support@casmen.jp">support@casmen.jp</a>
    </div>
</footer>
@endsection
