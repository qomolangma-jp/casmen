@extends('layouts.user')

@section('title', 'CASMEN｜らくらくセルフ面接')

@push('styles')
<style>
    /* ビデオプレビューの調整 */
    #preview-video {
        /* user.cssのサイズ(17.3rem x 29.2rem)を強制適用 */
        width: 17.3rem !important;
        height: 29.2rem !important;
        object-fit: contain !important; /* 枠内に全体を収める */
        background-color: #000; /* 余白を黒くする */
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/admin/js/jquery-3.7.1.min.js') }}"></script>
<script>
    // カメラを起動
    let previewStream = null;

    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user'
                },
                audio: true
            });

            previewStream = stream;
            const videoElement = document.getElementById('preview-video');
            videoElement.srcObject = stream;
            videoElement.setAttribute('playsinline', ''); // iOS対応
            videoElement.play();
        } catch (error) {
            console.error('カメラの起動に失敗しました:', error);
            alert('カメラとマイクへのアクセスを許可してください。\nブラウザの設定を確認してください。');
        }
    }

    // ページ読み込み時にカメラを起動
    window.addEventListener('DOMContentLoaded', startCamera);

    // ページを離れる時にカメラを停止
    window.addEventListener('beforeunload', () => {
        if (previewStream) {
            previewStream.getTracks().forEach(track => track.stop());
        }
    });
</script>
@endpush

@section('content')
<header class="header">
    <div class="header__container">
        <img src="{{ asset('assets/user/img/logo2.png') }}" alt="らくらくセルフ面接">
    </div>
</header>

<main class="main">
    <div class="main__container">
        <div class="instruction instruction__interview bg-frame">
            <div class="instruction__inner">
                <div class="instruction__video-imgs">
                    <video id="preview-video" autoplay playsinline muted></video>
                    <div class="instruction__imgs">
                        <div class="instruction__bubble-imgs">
                            <img src="{{ asset('assets/user/img/bubble.png') }}" class="instruction__bubble-img" alt="吹き出し">
                            <img src="{{ asset('assets/user/img/display.png') }}" class="instruction__bubble-text" alt="映ってるかな？">
                        </div>
                        <div class="instruction__character-img">
                            <img src="{{ asset('assets/user/img/bear.png') }}" class="instruction__bear-img" alt="クマのキャラクター">
                        </div>
                    </div>
                </div>

                <small class="instruction__notice">※プレビュー画面のサイズ変更はできません</small>
                <p class="instruction__text">準備ができましたら<br>【セルフ面接スタート】ボタン<br>をタップしてください。<br>3秒後に質問がスタートします。</p>

            </div>
        </div>
        <a href="{{ route('record.interview', ['token' => $token]) }}" class="main__btn start-btn">セルフ面接スタート</a>

        @if(($entry->interrupt_retake_count ?? 0) >= 1)
            <a href="#" class="main__btn disabled-btn" style="background-color: #ccc; cursor: not-allowed; pointer-events: none; margin-top: 2.6rem;">最初からやり直す<span class="remaining-chance">（残り0回）</span></a>
        @endif
    </div>
</main>

<footer class="footer">
    <div class="footer__container">
        <p>ご不明点やトラブルがあれば、下記のサポートまでお気軽にご連絡ください。</p>
        <a href="mailto:support@casmen.jp">support@casmen.jp</a>
    </div>
</footer>
@endsection
