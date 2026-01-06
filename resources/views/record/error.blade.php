@extends('layouts.user')

@section('title', 'CASMEN｜らくらくセルフ面接')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush

@section('content')
<header class="header">
    <div class="header__container">
        <img src="{{ asset('assets/user/img/logo2.png') }}" alt="らくらくセルフ面接">
    </div>
</header>

<main class="main">
    <div class="main__container">
        <div class="instruction bg-frame">
            <div class="instruction__error-inner">
                <span class="instruction__error-message"><i class="bi bi-exclamation-triangle-fill"></i>エラーが発生しました</span>

                @if(isset($errorMessage) && $errorMessage !== 'エラーが発生しました')
                    <p class="error-detail" style="color: red; font-weight: bold; margin-bottom: 10px;">{!! $errorMessage !!}</p>
                @endif

                <p>解決しない場合は、サポートまでご連絡<br>ください。</p>
                <a href="mailto:support@casmen.jp">support@casmen.jp</a>
            </div>
        </div>

        <a href="{{ route('top.index') }}" class="main__btn">TOPへ戻る</a>
    </div>
</main>

<footer class="footer">
    <div class="footer__container">
        <p>ご不明点やトラブルがあれば、下記のサポートまでお気軽にご連絡ください。</p>
        <a href="mailto:support@casmen.jp">support@casmen.jp</a>
    </div>
</footer>
@endsection
