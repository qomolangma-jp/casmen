@extends('layouts.admin')

@section('title', 'CASMEN｜面接URL送信完了')

@section('content')
<main>
    <div class="main-container">
        <div class="breadcrumbs">
            <span><a href="{{ route('admin.dashboard') }}">TOP</a> > <a href="{{ route('admin.entry.index') }}">応募者一覧</a> > 面接URL送信完了</span>
        </div>
        <div class="ray-content registration-content">
            <h2>面接URL送信完了</h2>
            <div class="complete-message">
                <p>面接URLを送信しました。</p>
                <p>応募者名: {{ $entry->name }}</p>
                @if($sentMethod === 'email')
                    <p>送信先: {{ $entry->email }} (メール)</p>
                @elseif($sentMethod === 'sms')
                    <p>送信先: {{ $entry->tel }} (SMS)</p>
                @endif
            </div>
            <div class="form-actions">
                <a href="{{ route('admin.link.create') }}" class="btn-secondary">続けて発行する</a>
                <a href="{{ route('admin.entry.index') }}" class="btn-primary">応募者一覧へ</a>
            </div>
        </div>
    </div>
</main>
@endsection
