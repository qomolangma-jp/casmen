@extends('layouts.user')

@section('title', 'CASMEN｜らくらくセルフ面接')

@push('scripts')
<script src="{{ asset('assets/admin/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/user/js/main.js') }}"></script>
<script>
    // カメラを起動
    let previewStream = null;

    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: { ideal: 1280 },
                    height: { ideal: 720 },
                    facingMode: 'user'
                },
                audio: true
            });

            previewStream = stream;
            const videoElement = document.getElementById('preview-video');
            videoElement.srcObject = stream;
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
<header>
    <div class="header-container preview-header-container">
        <div class="header-container-inner count-logo">
            <span class="logo-lines">
                <img src="{{ asset('assets/user/img/logo3.png') }}" alt="らくらくセルフ面接">
            </span>
            <span class="count">{{ $questions->count() }}</span>
        </div>
    </div>
</header>
<main>
    <div class="main-container preview-container">
        <div class="main-content preview-content">
            <div class="medium-description">
                <p class="preview-text">準備ができましたら<br>【セルフ面接スタート】ボタン<br>をタップしてください。<br>3秒後に質問がスタートします。</p>
            </div>
            <div class="video">
                <video id="preview-video" autoplay muted></video>
                <div class="character">
                    <div class="bubble">
                        <img src="{{ asset('assets/user/img/display.png') }}" class="bubble-line" alt="映ってるかな？">
                    </div>
                    <img src="{{ asset('assets/user/img/bear.png') }}" class="little-bear" alt="クマのキャラクター">
                </div>
            </div>
        </div>

        <div class="question-counter">
            <span class="current-question">1</span>
            <span class="question-num">{{ $questions->count() }}</span>
        </div>
        <a href="{{ route('record.interview', ['token' => $token]) }}" class="purple-btn start-btn">セルフ面接スタート</a>
    </div>
</main>
<footer>
    <div class="footer-container">
        <p>ご不明点やトラブルがあれば、下記のサポートまでお気軽にご連絡ください。</p>
        <a href="mailto:support@casmen.jp">support@casmen.jp</a>
    </div>
</footer>
@endsection
