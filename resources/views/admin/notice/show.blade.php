@extends('layouts.admin')

@section('title', 'CASMEN｜お知らせ詳細')

@section('content')
<main>
    <div class="main-container">
        <div class="breadcrumbs">
            <span><a href="{{ route('admin.dashboard') }}">TOP</a> > <a href="{{ route('admin.notice.index') }}">お知らせ一覧</a> > お知らせ詳細</span>
        </div>
        <div class="ray-content notice">
            <span class="notice-title">お知らせ</span>
            <article>
                <div class="article-title">
                    {{-- NEW badge logic: created within 3 days --}}
                    @if(\Carbon\Carbon::parse($notice->created_at)->diffInDays(now()) < 3)
                        <h3 class="new">{{ $notice->title }}</h3>
                    @else
                        <h3>{{ $notice->title }}</h3>
                    @endif
                    <time datetime="{{ $notice->created_at }}">{{ \Carbon\Carbon::parse($notice->created_at)->format('Y/m/d H:i') }}</time>
                </div>
                <div class="article-text">
                    <p>
                        {!! nl2br(e($notice->text)) !!}
                    </p>
                </div>
            </article>
            <div class="back">
                <a href="{{ route('admin.notice.index') }}">
                    <img src="{{ asset('assets/admin/img/left-chevron.png') }}" alt="戻るアイコン">
                    <span>一覧に戻る</span>
                </a>
            </div>
        </div>
        <div class="privacy-policy">
            <a href="{{ route('company.policy') }}" target="_blank">個人情報の取り扱いについて</a>
        </div>
    </div>
</main>
@endsection
