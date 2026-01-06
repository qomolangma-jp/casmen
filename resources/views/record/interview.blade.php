@extends('layouts.user')

@section('title', 'CASMEN｜らくらくセルフ面接')

@push('styles')
<style>
    /* 開始前カウントダウン用オーバーレイ */
    #start-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        font-size: 12rem;
        font-weight: bold;
        font-family: 'M PLUS Rounded 1c', sans-serif;
    }

    /* ビデオプレビューの調整 */
    #interview-video {
        /* user.cssのサイズ(17.3rem x 29.2rem)を強制適用 */
        width: 17.3rem !important;
        height: 29.2rem !important;
        object-fit: contain !important; /* 枠内に全体を収める */
        background-color: #000; /* 余白を黒くする */
    }

    /* ローディングスピナー */
    .loading-spinner {
<<<<<<< HEAD
        width: 60px;
        height: 60px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #ffffff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto;
=======
        display: inline-block;
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #1976d2;
        border-radius: 50%;
        animation: spin 1s linear infinite;
>>>>>>> 0c63ab33bf4160433b68b530c2ffab7cdc11506d
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    #upload-status {
<<<<<<< HEAD
        position: fixed;
        top: 45%;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.85);
        z-index: 9999;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
=======
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        z-index: 1000;
>>>>>>> 0c63ab33bf4160433b68b530c2ffab7cdc11506d
    }

    .instruction__video {
        position: relative;
    }

    #upload-status p {
        color: white;
<<<<<<< HEAD
        font-size: 2rem;
        font-weight: bold;
        margin: 1.5rem 0 0 0;
        text-align: center;
        line-height: 1.6;
=======
        font-size: 1.6rem;
        font-weight: bold;
        margin: 1rem 0 0 0;
>>>>>>> 0c63ab33bf4160433b68b530c2ffab7cdc11506d
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/admin/js/jquery-3.7.1.min.js') }}"></script>
<!-- <script src="{{ asset('assets/user/js/main.js') }}"></script> -->
<script>
    const questions = @json($questions);
    const token = "{{ $token }}";
    const totalQuestions = questions.length;

    let currentQuestionIndex = 0;
    let stream = null;
    let mediaRecorder = null;
    let recordedChunks = []; // 現在の質問の録画チャンク
    let countdownInterval = null;
    let questionTimer = null;
    let recordingStartTime = null;
    let currentQuestionRecordingStart = null; // 現在の質問の録画開始時刻
    let recordedMimeType = ''; // 実際に使用されたmimeTypeを保存
    let wakeLock = null; // 画面ロック防止用
    let uploadedCount = 0; // アップロード完了数

    // Wake Lock API (画面スリープ防止)
    async function requestWakeLock() {
        try {
            if ('wakeLock' in navigator) {
                wakeLock = await navigator.wakeLock.request('screen');
                console.log('Wake Lock is active');

                wakeLock.addEventListener('release', () => {
                    console.log('Wake Lock released');
                });
            }
        } catch (err) {
            console.error(`Wake Lock error: ${err.name}, ${err.message}`);
        }
    }

    function releaseWakeLock() {
        if (wakeLock !== null) {
            wakeLock.release().catch(err => console.error(err));
            wakeLock = null;
        }
    }

    // カメラとマイクの起動
    async function startCamera() {
        try {
            // カメラストリームを取得（解像度を320x240、フレームレート15fpsに制限して容量を極限まで節約）
            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user',
                    width: { ideal: 320 },
                    height: { ideal: 240 },
                    frameRate: { ideal: 15 }
                },
                audio: true
            });

            const videoElement = document.getElementById('interview-video');
            videoElement.srcObject = stream;
            videoElement.muted = true;
            videoElement.setAttribute('playsinline', ''); // iOS対応
            await videoElement.play();

            // 最初の質問を表示（タイマーは開始しない）
            displayQuestion(0);

            // カメラ起動成功後、3秒カウントダウンしてから録画開始
            startCountdown();
        } catch (error) {
            console.error('カメラの起動に失敗しました:', error);
            alert('カメラとマイクへのアクセスを許可してください。');
            window.location.href = "{{ route('record.error') }}?token=" + token;
        }
    }

    // 3秒カウントダウン（録画前の準備時間）
    function startCountdown() {
        let countdown = 3;
        const overlay = document.getElementById('start-overlay');
        const countdownElement = document.getElementById('overlay-countdown');

        // オーバーレイを表示
        overlay.style.display = 'flex';
        countdownElement.textContent = countdown;

        countdownInterval = setInterval(() => {
            countdown--;

            if (countdown === 0) {
                clearInterval(countdownInterval);
                // カウントが0になったらオーバーレイを非表示にし、録画開始して最初の質問を表示
                overlay.style.display = 'none';
                startRecording();
                showFirstQuestion();
            } else {
                countdownElement.textContent = countdown;
            }
        }, 1000);
    }

    // 録画開始（継続録画）
    function startRecording() {
        // 画面スリープ防止をリクエスト
        requestWakeLock();

        try {
            // iPhone/Safari判定
            const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
            const isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);

            // ビットレートを250kbpsに制限して容量を極限まで節約（安定性重視）
            let options = {
                videoBitsPerSecond: 250000
            };

            // iPhone/Safariの場合はmp4を最優先
            if (isIOS || isSafari) {
                const mp4Types = [
                    'video/mp4',
                    'video/mp4;codecs=h264',
                    'video/mp4;codecs=avc1'
                ];

                for (const type of mp4Types) {
                    if (MediaRecorder.isTypeSupported(type)) {
                        options.mimeType = type;
                        console.log('iPhone/Safari検出、mp4を使用:', type);
                        break;
                    }
                }
            }

            // mp4が使えない場合、WebMを試す
            if (!options.mimeType) {
                const webmTypes = [
                    'video/webm;codecs=vp9,opus',
                    'video/webm;codecs=vp8,opus',
                    'video/webm'
                ];

                for (const type of webmTypes) {
                    if (MediaRecorder.isTypeSupported(type)) {
                        options.mimeType = type;
                        console.log('WebMを使用:', type);
                        break;
                    }
                }
            }

            // どれも使えない場合はデフォルト
            if (!options.mimeType) {
                console.warn('サポートされているmimeTypeが見つかりません。デフォルトを使用します。');
            }

            mediaRecorder = new MediaRecorder(stream, options);
            recordedMimeType = mediaRecorder.mimeType; // 実際に使用されたmimeTypeを記録
            console.log('最終的に使用されたmimeType:', recordedMimeType);

            mediaRecorder.ondataavailable = (event) => {
                if (event.data && event.data.size > 0) {
                    recordedChunks.push(event.data);
                    console.log('データ取得:', event.data.size, 'bytes');
                }
            };

            mediaRecorder.onstop = () => {
                console.log('録画停止、チャンク数:', recordedChunks.length);
                // 質問ごとに即座にアップロード
                uploadCurrentQuestion();
            };

            // 定期的にデータを取得（1秒ごと）
            mediaRecorder.start(1000);
            recordingStartTime = Date.now();

            // デバイス情報を記録
            const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
            const isPortrait = window.matchMedia('(orientation: portrait)').matches;
            const videoTrack = stream.getVideoTracks()[0];
            const settings = videoTrack.getSettings();

            console.log('録画開始 - mimeType:', options.mimeType, 'startTime:', recordingStartTime);
            console.log('デバイス情報:', {
                isMobile,
                isPortrait,
                videoWidth: settings.width,
                videoHeight: settings.height,
                userAgent: navigator.userAgent
            });
        } catch (error) {
            console.error('録画の開始に失敗しました:', error);
            alert('録画を開始できませんでした。\nエラー: ' + error.message);
        }
    }

    // 最初の質問を表示
    function showFirstQuestion() {
        displayQuestion(0);
        // 質問ごとの録画開始時刻を記録
        currentQuestionRecordingStart = Date.now();
        // 質問ごとにチャンクをリセット
        recordedChunks = [];
        startQuestionTimer();
    }

    // 質問を表示
    function displayQuestion(index) {
        if (index >= totalQuestions) {
            // 全ての質問が終了
            stopRecording();
            return;
        }

        currentQuestionIndex = index;
        const question = questions[index];

        // 質問番号 (1始まり)
        document.getElementById('question-increment').textContent = index + 1;

        // 質問文
        // 改行コードを<br>に変換して表示
        document.getElementById('question-text').innerHTML = question.q.replace(/\r\n/g, '<br>').replace(/[\n\r]/g, '<br>');

        // 残り質問数または「最後の質問」表示
        const countdownElement = document.querySelector('.instruction__countdown');

        if (index === totalQuestions - 1) {
            // 最後の質問の場合
            countdownElement.innerHTML = `質問完了まで残り：<span class="instruction__current-status"><span id="current-time">8</span>秒</span>｜最後の質問`;
        } else {
            // 通常の場合
            const remainingQuestions = totalQuestions - (index + 1);
            countdownElement.innerHTML = `次の質問まで残り：<span class="instruction__current-status"><span id="current-time">8</span>秒</span>｜残り質問数：<span class="instruction__current-status"><span id="question-decrement">${remainingQuestions}</span>問</span>`;
        }

        // カウントダウンを8秒に設定
        document.getElementById('current-time').textContent = '8';
    }

    // 質問タイマー（8秒後に次の質問へ）
    function startQuestionTimer() {
        let countdown = 8;
        const countdownElement = document.getElementById('current-time');

        // 最後の質問の場合はカウントダウン表示がないので更新しない
        if (countdownElement) {
            countdownElement.textContent = countdown;
        }

        if (questionTimer) {
            clearInterval(questionTimer);
        }

        questionTimer = setInterval(() => {
            countdown--;

            if (countdown === 0) {
                clearInterval(questionTimer);

                // 現在の質問の録画を停止（アップロードはonstopで実行）
                if (mediaRecorder && mediaRecorder.state === 'recording') {
                    mediaRecorder.stop();
                }

                // 次の質問があるかチェックは uploadCurrentQuestion() 内で行う
            } else {
                if (countdownElement) {
                    countdownElement.textContent = countdown;
                }
            }
        }, 1000);
    }

    // 録画停止
    function stopRecording() {
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
            mediaRecorder.stop();
        }

        if (questionTimer) {
            clearInterval(questionTimer);
        }
    }

    // 現在の質問の録画をアップロード
    async function uploadCurrentQuestion() {
        if (recordedChunks.length === 0) {
            console.error('録画データがありません');
            alert('録画データが保存されていません。もう一度お試しください。');
            window.location.href = "{{ route('record.interview-preview') }}?token=" + token;
            return;
        }

        // 実際に録画されたmimeTypeを使用
        const blobType = recordedMimeType || 'video/webm';
        const blob = new Blob(recordedChunks, { type: blobType });
        console.log('質問' + (currentQuestionIndex + 1) + 'のBlob作成:', blob.size, 'bytes, type:', blob.type);

        if (blob.size === 0) {
            console.error('録画データのサイズが0です');
            alert('録画データが空です。もう一度お試しください。');
            window.location.href = "{{ route('record.interview-preview') }}?token=" + token;
            return;
        }

        // ファイル拡張子を決定
        const extension = blobType.includes('mp4') ? 'mp4' : 'webm';
        const questionNumber = currentQuestionIndex + 1;

        // アップロード中表示を表示
        const uploadStatus = document.getElementById('upload-status');
        const uploadMessage = document.getElementById('upload-message');
        if (uploadStatus && uploadMessage) {
            uploadMessage.textContent = `質問${questionNumber}の動画をアップロード中...`;
            uploadStatus.style.display = 'block';
        }

        // FormDataを作成
        const formData = new FormData();
        formData.append('video', blob, `interview_question_${questionNumber}.${extension}`);
        formData.append('question_number', questionNumber);
        formData.append('total_questions', totalQuestions);
        formData.append('token', token);
        formData.append('_token', '{{ csrf_token() }}');

        try {
            // サーバーにアップロード
            const response = await fetch('{{ route("record.upload") }}', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            // アップロード中表示を非表示
            if (uploadStatus) {
                uploadStatus.style.display = 'none';
            }

            if (data.success) {
                console.log(`質問${questionNumber}の動画アップロード成功:`, data.file_path);
                uploadedCount++;

                // メモリ解放
                recordedChunks = [];

                // 次の質問または完了処理
                if (currentQuestionIndex + 1 < totalQuestions) {
                    // 次の質問へ
                    const nextIndex = currentQuestionIndex + 1;
                    displayQuestion(nextIndex);
                    currentQuestionRecordingStart = Date.now();

                    // MediaRecorderを再開
                    if (mediaRecorder && mediaRecorder.state === 'inactive') {
                        mediaRecorder.start(1000);
                    }

                    startQuestionTimer();
                } else {
                    // 全質問完了
                    console.log('全質問のアップロード完了');
                    completeInterview();
                }
            } else {
                console.error(`質問${questionNumber}の動画アップロード失敗:`, data.message);
                alert(`質問${questionNumber}の動画保存に失敗しました: ` + data.message);
                window.location.href = "{{ route('record.interview-preview') }}?token=" + token;
            }
        } catch (error) {
            // アップロード中表示を非表示
            if (uploadStatus) {
                uploadStatus.style.display = 'none';
            }
            console.error(`質問${questionNumber}の動画アップロードエラー:`, error);
            alert('動画のアップロード中にエラーが発生しました。\nもう一度お試しください。');
            window.location.href = "{{ route('record.interview-preview') }}?token=" + token;
        }
    }

    // 全質問完了処理
    function completeInterview() {
        // カメラを停止
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }

        // Wake Lock解放
        releaseWakeLock();

        // 完了ページへ遷移
        window.location.href = "{{ route('record.complete') }}?token=" + token;
    }

    // ページ読み込み時にカメラ起動
    window.addEventListener('DOMContentLoaded', () => {
        startCamera();
    });

    // ページを離れる時にカメラとタイマーを停止
    window.addEventListener('beforeunload', () => {
        releaseWakeLock(); // Wake Lock解放
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        if (countdownInterval) clearInterval(countdownInterval);
        if (questionTimer) clearInterval(questionTimer);
    });

    // 途中やり直しボタンのクリックイベント
    document.addEventListener('DOMContentLoaded', () => {
        const interruptBtn = document.getElementById('interrupt-btn');
        if (interruptBtn) {
            interruptBtn.addEventListener('click', async (e) => {
                e.preventDefault();

                if (!confirm('最初からやり直しますか？\n※現在の進行状況はリセットされ、やり直し回数が1回消費されます。')) {
                    return;
                }

                // ボタンを無効化
                interruptBtn.classList.add('disabled-btn');
                interruptBtn.style.pointerEvents = 'none';

                try {
                    const formData = new FormData();
                    formData.append('token', token);
                    formData.append('_token', '{{ csrf_token() }}');

                    const response = await fetch('{{ route("record.interrupt") }}', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        // IndexedDBをクリア
                        try {
                            const db = await openDB();
                            const transaction = db.transaction(storeName, 'readwrite');
                            const store = transaction.objectStore(storeName);
                            store.clear();
                        } catch (err) {
                            console.error('IndexedDBクリアエラー:', err);
                        }

                        window.location.href = "{{ route('record.interview-preview') }}?token=" + token + "&t=" + new Date().getTime();
                    } else {
                        alert(result.message || 'やり直しの開始に失敗しました。');
                    }
                } catch (error) {
                    console.error('やり直しエラー:', error);
                    alert('通信エラーが発生しました。もう一度お試しください。');
                }
            });
        }
    });
</script>
@endpush

@section('content')
<body class="page-answer-countdown-custom">
<!-- 開始前カウントダウン用オーバーレイ -->
<div id="start-overlay">
    <span id="overlay-countdown">3</span>
</div>

<header class="header">
    <div class="header__container">
        <img src="{{ asset('assets/user/img/logo2.png') }}" alt="らくらくセルフ面接">
    </div>
</header>

<main class="main">
    <div class="main__container">
        <div class="instruction instruction__interview bg-frame">
            <div class="instruction__inner">
                <div class="instruction__video">
                    <video id="interview-video" autoplay playsinline muted></video>

                    <!-- アップロード中表示 -->
<<<<<<< HEAD
                    <div id="upload-status" style="display: none;">
=======
                    <div id="upload-status" style="display: none; text-align: center;">
>>>>>>> 0c63ab33bf4160433b68b530c2ffab7cdc11506d
                        <div class="loading-spinner"></div>
                        <p>
                            <span id="upload-message">動画をアップロード中...</span>
                        </p>
                    </div>
                </div>

                <span class="instruction__notice">Q.<span id="question-increment">1</span></span>
                <p class="instruction__countdown">次の質問まで残り：<span class="instruction__current-status"><span id="current-time">10</span>秒</span>｜残り質問数：<span class="instruction__current-status"><span id="question-decrement">{{ count($questions) - 1 }}</span>問</span></p>
                <p class="instruction__question" id="question-text"></p>

            </div>
        </div>
        @if(($entry->interrupt_retake_count ?? 0) < 1)
            <a href="#" id="interrupt-btn" class="main__btn">最初からやり直す<span class="remaining-chance">（残り{{ 1 - ($entry->interrupt_retake_count ?? 0) }}回）</span></a>
        @else
            <a href="#" class="main__btn disabled-btn" style="background-color: #ccc; cursor: not-allowed; pointer-events: none;">最初からやり直す<span class="remaining-chance">（残り0回）</span></a>
        @endif
    </div>
</main>

<footer class="footer">
    <div class="footer__container">
        <p>ご不明点やトラブルがあれば、下記のサポートまでお気軽にご連絡ください。</p>
        <a href="mailto:support@casmen.jp">support@casmen.jp</a>
    </div>
</footer>
</body>
@endsection
