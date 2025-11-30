@extends('layouts.user')

@section('title', 'CASMEN｜らくらくセルフ面接')

@push('scripts')
<script src="{{ asset('assets/admin/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/user/js/main.js') }}"></script>
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
            videoElement.muted = true;
            await videoElement.play();

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
        const countdownElement = document.getElementById('answer-countdown');
        countdownElement.textContent = countdown;

        countdownInterval = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;

            if (countdown === 0) {
                clearInterval(countdownInterval);
                // カウントが0になったら録画開始して最初の質問を表示
                startRecording();
                showFirstQuestion();
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

        document.getElementById('question-index').textContent = index + 1;
        document.getElementById('question-text').textContent = question.q;
        document.getElementById('current-index').textContent = index + 1;

        // カウントダウンを5秒に設定
        document.getElementById('answer-countdown').textContent = '5';
    }

    // 質問タイマー（5秒後に次の質問へ）
    function startQuestionTimer() {
        let countdown = 5;
        const countdownElement = document.getElementById('answer-countdown');
        countdownElement.textContent = countdown;

        if (questionTimer) {
            clearInterval(questionTimer);
        }

        questionTimer = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;

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

    // 録画データを保存してconfirmページへ遷移
    function saveRecordingAndRedirect() {
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

        // 録画データをセッションストレージに保存
        const reader = new FileReader();
        reader.onloadend = () => {
            sessionStorage.setItem('recordedVideo', reader.result);
            sessionStorage.setItem('videoToken', token);
            sessionStorage.setItem('questionTimestamps', JSON.stringify(questionTimestamps));
            console.log('セッションストレージに保存完了');

            // カメラを停止
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }

            // confirmページへ遷移
            window.location.href = "{{ route('record.confirm') }}?token=" + token;
        };
        reader.onerror = (error) => {
            console.error('FileReader エラー:', error);
            alert('録画データの読み込みに失敗しました。');
        };
        reader.readAsDataURL(blob);
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
<body class="page-answer-countdown">
<header>
    <div class="header-container preview-header-container">
        <div class="header-container-inner count-logo">
            <span class="logo-lines">
                <img src="{{ asset('assets/user/img/logo3.png') }}" alt="らくらくセルフ面接">
            </span>
            <span id="answer-countdown" class="count">3</span>
        </div>
    </div>
</header>
<main>
    <div class="main-container preview-container">
        <div class="main-content preview-content">
            <div class="medium-description">
                <span class="question-card-num">Q.<span id="question-index">1</span></span>
                <p class="question-text" id="question-text">{{ $questions[0]->q ?? '最近ハマっていることは？' }}</p>
            </div>
            <div class="video">
                <video id="interview-video" autoplay muted></video>
                <div class="character">
                    <div class="bubble">
                        <img src="{{ asset('assets/user/img/feel-free.png') }}" alt="気楽に答えてね">
                    </div>
                    <img src="{{ asset('assets/user/img/bear.png') }}" class="little-bear" alt="クマのキャラクター">
                </div>
            </div>
        </div>

        <div class="question-counter">
            <span id="current-index" class="current-question">1</span>
            <span class="question-num">{{ $questions->count() }}</span>
        </div>
        <a href="{{ route('record.howto', ['token' => $token]) }}" class="purple-btn start-btn">最初からやり直す</a>
    </div>
</main>
<footer>
    <div class="footer-container">
        <p>ご不明点やトラブルがあれば、下記のサポートまでお気軽にご連絡ください。</p>
        <a href="mailto:support@casmen.jp">support@casmen.jp</a>
    </div>
</footer>
</body>
@endsection
