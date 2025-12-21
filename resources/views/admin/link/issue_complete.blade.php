@extends('layouts.admin')

@section('title', 'CASMEN｜面接URL発行完了')

@section('content')
<main>
    <div class="main-container">
        <div class="breadcrumbs">
            <span><a href="{{ route('admin.dashboard') }}">TOP</a> > <a href="{{ route('admin.entry.index') }}">応募者一覧</a> > 面接URL発行完了</span>
        </div>
        <div class="ray-content registration-content">
            <h2>面接URL発行完了</h2>
            <div class="complete-message">
                <p>面接URLを発行しました。</p>
                <p>応募者名: {{ $entry->name }}</p>
            </div>

            <p class="copy-url-description">LINEやSNSで送る場合は、下記の面接URLをコピーしてご利用ください。</p>
            <div class="copy-url">
                <input id="url-display" class="display" type="url" readonly value="{{ $interviewUrl }}">
                <span id="copy" onclick="copyToClipboard()">
                    <img src="{{ asset('assets/admin/img/copy-icon.png') }}" alt="コピーアイコン">
                </span>
            </div>

            <div class="form-actions" style="margin-top: 2rem;">
                <a href="{{ route('admin.link.create') }}" class="btn-secondary">続けて発行する</a>
                <a href="{{ route('admin.entry.index') }}" class="btn-primary">応募者一覧へ</a>
            </div>
        </div>
    </div>
</main>
<script>
    function copyToClipboard() {
        var copyText = document.getElementById("url-display");
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */
        document.execCommand("copy");
        alert("URLをコピーしました: " + copyText.value);
    }
</script>
@endsection
