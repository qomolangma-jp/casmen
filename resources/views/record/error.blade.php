@extends('layouts.user')

@section('title', 'CASMEN｜らくらくセルフ面接')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush

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
        <div class="main-content error-content">
            <div class="error-lg-description">
                <span><i class="bi bi-exclamation-triangle-fill"></i>エラーが発生しました</span>
                <p>{{ $errorMessage ?? '予期しないエラーが発生しました。' }}</p>
                <p>解決しない場合は、サポートまでご連絡<br>ください。</p>
                <a href="mailto:support@casmen.jp">support@casmen.jp</a>
            </div>
        </div>
        <a href="{{ route('top.index') }}" class="purple-btn">TOPへ戻る</a>
    </div>
</main>
<footer>
    <div class="footer-container">
        <p>ご不明点やトラブルがあれば、下記のサポートまでお気軽にご連絡ください。</p>
        <a href="mailto:support@casmen.jp">support@casmen.jp</a>
    </div>
</footer>
@endsection
