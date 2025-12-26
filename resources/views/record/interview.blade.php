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
    let recordedChunks = [];
    let countdownInterval = null;
    let questionTimer = null;
    let recordingStartTime = null;
    let questionTimestamps = [];

    // カメラとマイクの起動
    async function startCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: { ideal: 1280 },
                    height: { ideal: 720 },
                    facingMode: 'user'
                },
                audio: true
            });

            const videoElement = document.getElementById('interview-video');
            videoElement.srcObject = stream;
            // videoElement.muted = true; // 音声を有効にするためコメントアウト
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
        try {
            const options = { mimeType: 'video/webm;codecs=vp9,opus' };

            if (!MediaRecorder.isTypeSupported(options.mimeType)) {
                options.mimeType = 'video/webm';
            }

            mediaRecorder = new MediaRecorder(stream, options);

            mediaRecorder.ondataavailable = (event) => {
                if (event.data && event.data.size > 0) {
                    recordedChunks.push(event.data);
                    console.log('データ取得:', event.data.size, 'bytes');
                }
            };

            mediaRecorder.onstop = () => {
                console.log('録画停止、チャンク数:', recordedChunks.length);
                // 録画完了後、confirmページへ遷移
                saveRecordingAndRedirect();
            };

            // 定期的にデータを取得（1秒ごと）
            mediaRecorder.start(1000);
            recordingStartTime = Date.now();
            console.log('録画開始 - mimeType:', options.mimeType, 'startTime:', recordingStartTime);
        } catch (error) {
            console.error('録画の開始に失敗しました:', error);
            alert('録画を開始できませんでした。\nエラー: ' + error.message);
        }
    }

    // 最初の質問を表示
    function showFirstQuestion() {
        displayQuestion(0);
        // 質問開始時刻を記録
        const timestamp = Date.now() - recordingStartTime;
        questionTimestamps.push({
            index: 0,
            question: questions[0].q,
            startTime: timestamp
        });
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
        document.getElementById('question-text').textContent = question.q;

        // 残り質問数または「最後の質問」表示
        const countdownElement = document.querySelector('.instruction__countdown');

        if (index === totalQuestions - 1) {
            // 最後の質問の場合
            countdownElement.innerHTML = `質問完了まで残り：<span class="instruction__current-status"><span id="current-time">10</span>秒</span>｜最後の質問`;
        } else {
            // 通常の場合
            const remainingQuestions = totalQuestions - (index + 1);
            countdownElement.innerHTML = `次の質問まで残り：<span class="instruction__current-status"><span id="current-time">10</span>秒</span>｜残り質問数：<span class="instruction__current-status"><span id="question-decrement">${remainingQuestions}</span>問</span>`;
        }

        // カウントダウンを10秒に設定 (how_to.htmlの記述に合わせて10秒に変更)
        // how_to.html: "1問につき約10秒"
        // interview.html: "9秒" (初期値)
        document.getElementById('current-time').textContent = '10';
    }

    // 質問タイマー（10秒後に次の質問へ）
    function startQuestionTimer() {
        let countdown = 10;
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

                // 次の質問へ
                if (currentQuestionIndex + 1 < totalQuestions) {
                    const nextIndex = currentQuestionIndex + 1;
                    displayQuestion(nextIndex);

                    // 質問開始時刻を記録
                    const timestamp = Date.now() - recordingStartTime;
                    questionTimestamps.push({
                        index: nextIndex,
                        question: questions[nextIndex].q,
                        startTime: timestamp
                    });

                    startQuestionTimer();
                } else {
                    // 全質問終了
                    stopRecording();
                }
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

    // IndexedDB Helper
    const dbName = 'InterviewDB';
    const storeName = 'videos';

    function openDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(dbName, 1);
            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                if (!db.objectStoreNames.contains(storeName)) {
                    db.createObjectStore(storeName);
                }
            };
            request.onsuccess = (event) => {
                resolve(event.target.result);
            };
            request.onerror = (event) => {
                reject(event.target.error);
            };
        });
    }

    async function saveToIndexedDB(key, value) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const transaction = db.transaction(storeName, 'readwrite');
            const store = transaction.objectStore(storeName);
            const request = store.put(value, key);
            request.onsuccess = () => resolve();
            request.onerror = (event) => reject(event.target.error);
        });
    }

    // 録画データを保存してconfirmページへ遷移
    async function saveRecordingAndRedirect() {
        if (recordedChunks.length === 0) {
            console.error('録画データがありません');
            alert('録画データが保存されていません。もう一度お試しください。');
            window.location.href = "{{ route('record.interview-preview') }}?token=" + token;
            return;
        }

        const blob = new Blob(recordedChunks, { type: 'video/webm' });
        console.log('Blob作成:', blob.size, 'bytes');

        if (blob.size === 0) {
            console.error('録画データのサイズが0です');
            alert('録画データが空です。もう一度お試しください。');
            window.location.href = "{{ route('record.interview-preview') }}?token=" + token;
            return;
        }

        try {
            // IndexedDBに保存
            await saveToIndexedDB('recordedVideo', blob);
            await saveToIndexedDB('videoToken', token);
            await saveToIndexedDB('questionTimestamps', JSON.stringify(questionTimestamps));
            console.log('IndexedDBに保存完了');

            // カメラを停止
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }

            // confirmページへ遷移
            window.location.href = "{{ route('record.confirm') }}?token=" + token;
        } catch (error) {
            console.error('IndexedDB保存エラー:', error);
            alert('録画データの保存に失敗しました。容量不足の可能性があります。');
        }
    }

    // ページ読み込み時にカメラ起動
    window.addEventListener('DOMContentLoaded', () => {
        startCamera();
    });

    // ページを離れる時にカメラとタイマーを停止
    window.addEventListener('beforeunload', () => {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        if (countdownInterval) clearInterval(countdownInterval);
        if (questionTimer) clearInterval(questionTimer);
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
                    <video id="interview-video" autoplay playsinline></video>
                </div>

                <span class="instruction__notice">Q.<span id="question-increment">1</span></span>
                <p class="instruction__countdown">次の質問まで残り：<span class="instruction__current-status"><span id="current-time">10</span>秒</span>｜残り質問数：<span class="instruction__current-status"><span id="question-decrement">{{ count($questions) - 1 }}</span>問</span></p>
                <p class="instruction__question" id="question-text"></p>

            </div>
        </div>
        <a href="{{ route('record.interview-preview', ['token' => $token]) }}" class="main__btn">最初からやり直す</a>
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
