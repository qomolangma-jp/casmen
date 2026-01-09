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
        height: 27.2rem !important;
        object-fit: contain !important; /* 枠内に全体を収める */
        background-color: #000; /* 余白を黒くする */
    }

    /* ローディングスピナー */
    .loading-spinner {
        width: 60px;
        height: 60px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #ffffff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    #upload-status {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.85);
        z-index: 9999;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .upload-statusinner {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .instruction__video {
        position: relative;
    }

    #upload-status p {
        color: white;
        font-size: 2rem;
        font-weight: bold;
        margin: 1.5rem 0 0 0;
        text-align: center;
        line-height: 1.6;
    }

    #upload-status.completed {
        background-color: rgba(0, 0, 0, 0.85);
    }

    #upload-status.completed .loading-spinner {
        display: none;
    }

    .upload-check {
        display: none;
        width: 60px;
        height: 60px;
        border: 5px solid #ffffff;
        border-radius: 50%;
        position: relative;
    }

    .upload-check::after {
        content: '';
        position: absolute;
        left: 18px;
        top: 8px;
        width: 15px;
        height: 30px;
        border: solid white;
        border-width: 0 5px 5px 0;
        transform: rotate(45deg);
    }

    #upload-status.completed .upload-check {
        display: block;
    }

    /* プレビュー画面のスタイル */
    #preview-screen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #ffffff;
        z-index: 10000;
        display: none;
        overflow-y: auto;
    }

    #preview-screen.active {
        display: block;
    }

    /* プレビュー用のビデオサイズ調整 */
    .instruction__preview-video {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .instruction__preview-video > video {
        height: 48rem;
        width: 28rem;
        margin: 0;
        object-fit: contain;
        background-color: #000;
    }

    /* プレビュー動画のカスタムコントロール */
    .preview-custom-controls {
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: rgba(0, 0, 0, 0.7);
        padding: 3px 15px;
        width: 28rem;
        box-sizing: border-box;
        position: absolute;
        bottom: 0px;
    }

    .preview-play-pause-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: white;
        font-size: 18px;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    .preview-play-pause-btn:hover {
        color: #ddd;
    }

    .preview-volume-container {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .preview-volume-icon {
        color: white;
        font-size: 16px;
        width: 20px;
        text-align: center;
    }

    .preview-volume-slider {
        width: 80px;
        cursor: pointer;
    }

    /* プレビューボタンのスタイル調整 */
    .instruction__preview-btns {
        display: flex;
        justify-content: center;
        margin: 0 auto;
        padding: 2.7rem 0 2rem 0;
        font-weight: 500;
    }

    /* 次の動画へボタンを非表示 */
    #preview-nav-btn {
        display: none !important;
    }

    /* 質問表示のスタイル */
    .preview-question-info {
        text-align: center;
        margin: 0;
    }

    .preview-question-label {
        font-size: 2.6rem;
        font-weight: bold;
        color: #9f4ecd;
        margin-bottom: 1rem;
    }

    .preview-question-text {
        font-size: 2rem;
        color: #333;
        line-height: 1.5;
        margin-bottom: 1.5rem;
    }

    /* 送信確認モーダル */
    #submit-confirm-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 10001; /* preview-screen(10000)より上 */
        display: none;
        justify-content: center;
        align-items: center;
    }

    #submit-confirm-modal .modal-content {
        background-color: #fff;
        padding: 5rem 4rem;
        border-radius: 1.5rem;
        width: 90%;
        max-width: 60rem;
        text-align: center;
        box-shadow: 0 0 20px rgba(0,0,0,0.2);
    }

    #submit-confirm-modal .modal-message {
        font-size: 2.0rem;
        font-weight: bold;
        margin-bottom: 4rem;
        color: #333;
        line-height: 1.5;
    }

    #submit-confirm-modal .modal-btns {
        display: flex;
        justify-content: space-between;
        gap: 2rem;
    }

    #submit-confirm-modal .modal-btn {
        flex: 1;
        padding: 1.8rem 0;
        border-radius: 5rem;
        font-size: 2rem;
        font-weight: bold;
        cursor: pointer;
        border: none;
        outline: none;
        appearance: none;
    }

    #submit-confirm-modal .modal-btn-cancel {
        background-color: #fff;
        color: #9f4ecd;
        border: 0.2rem solid #9f4ecd;
    }

    #submit-confirm-modal .modal-btn-ok {
        background-color: #9f4ecd;
        color: #fff;
        border: 0.2rem solid #9f4ecd;
    }

    /* 録り直し確認モーダル */
    #retake-confirm-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 10001;
        display: none;
        justify-content: center;
        align-items: center;
    }

    #retake-confirm-modal .modal-content {
        background-color: #fff;
        padding: 5rem 4rem;
        border-radius: 1.5rem;
        width: 90%;
        max-width: 60rem;
        text-align: center;
        box-shadow: 0 0 20px rgba(0,0,0,0.2);
    }

    #retake-confirm-modal .modal-message {
        font-size: 2.0rem;
        font-weight: bold;
        margin-bottom: 4rem;
        color: #333;
        line-height: 1.5;
    }

    #retake-confirm-modal .modal-btns {
        display: flex;
        justify-content: space-between;
        gap: 2rem;
    }

    #retake-confirm-modal .modal-btn {
        flex: 1;
        padding: 1.8rem 0;
        border-radius: 5rem;
        font-size: 2rem;
        font-weight: bold;
        cursor: pointer;
        border: none;
        outline: none;
        appearance: none;
    }

    #retake-confirm-modal .modal-btn-cancel {
        background-color: #fff;
        color: #9f4ecd;
        border: 0.2rem solid #9f4ecd;
    }

    #retake-confirm-modal .modal-btn-retake {
        background-color: #9f4ecd;
        color: #fff;
        border: 0.2rem solid #9f4ecd;
    }

    /* 途中やり直し確認モーダル */
    #interrupt-confirm-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 10001;
        display: none;
        justify-content: center;
        align-items: center;
    }

    #interrupt-confirm-modal .modal-content {
        background-color: #fff;
        padding: 5rem 4rem;
        border-radius: 1.5rem;
        width: 90%;
        max-width: 60rem;
        text-align: center;
        box-shadow: 0 0 20px rgba(0,0,0,0.2);
    }

    #interrupt-confirm-modal .modal-message {
        font-size: 2.0rem;
        font-weight: bold;
        margin-bottom: 4rem;
        color: #333;
        line-height: 1.5;
    }

    #interrupt-confirm-modal .modal-btns {
        display: flex;
        justify-content: space-between;
        gap: 2rem;
    }

    #interrupt-confirm-modal .modal-btn {
        flex: 1;
        padding: 1.8rem 0;
        border-radius: 5rem;
        font-size: 2rem;
        font-weight: bold;
        cursor: pointer;
        border: none;
        outline: none;
        appearance: none;
    }

    #interrupt-confirm-modal .modal-btn-cancel {
        background-color: #fff;
        color: #9f4ecd;
        border: 0.2rem solid #9f4ecd;
    }

    #interrupt-confirm-modal .modal-btn-interrupt {
        background-color: #9f4ecd;
        color: #fff;
        border: 0.2rem solid #9f4ecd;
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

    // デバッグ: questions配列の構造を確認
    console.log('=== questions配列の構造 ===');
    console.log('questions:', questions);
    if (questions.length > 0) {
        console.log('questions[0]:', questions[0]);
        console.log('questions[0]のキー:', Object.keys(questions[0]));
    }

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
    let uploadedVideos = []; // アップロードされた動画の情報を保存

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
            // 自動再生はせず、ユーザーが再生ボタンを押すまで待機
            // await videoElement.play();

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
                // 5問ごとまたは最後の質問でアップロード
                uploadBatchQuestions();
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
        // チャンクは最初から累積する（5問ごとにリセット）
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
            countdownElement.innerHTML = `質問完了まで残り：<span class="instruction__current-status"><span id="current-time">6</span>秒</span>｜最後の質問`;
        } else {
            // 通常の場合
            const remainingQuestions = totalQuestions - (index + 1);
            countdownElement.innerHTML = `次の質問まで残り：<span class="instruction__current-status"><span id="current-time">6</span>秒</span>｜残り質問数：<span class="instruction__current-status"><span id="question-decrement">${remainingQuestions}</span>問</span>`;
        }

        // カウントダウンを6秒に設定
        document.getElementById('current-time').textContent = '6';
    }

    // 質問タイマー（6秒後に次の質問へ）
    function startQuestionTimer() {
        let countdown = 6;
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

                // 5問ごとまたは最後の質問で録画を停止
                const isLastQuestion = currentQuestionIndex === totalQuestions - 1;
                const isFifthQuestion = (currentQuestionIndex + 1) % 5 === 0;

                if (isLastQuestion || isFifthQuestion) {
                    if (mediaRecorder && mediaRecorder.state === 'recording') {
                        mediaRecorder.stop();
                    }
                } else {
                    // 5問ごとでなければ次の質問へ進む
                    moveToNextQuestion();
                }
            } else {
                if (countdownElement) {
                    countdownElement.textContent = countdown;
                }
            }
        }, 1000);
    }

    // 次の質問へ遷移
    function moveToNextQuestion() {
        if (currentQuestionIndex + 1 < totalQuestions) {
            const nextIndex = currentQuestionIndex + 1;
            displayQuestion(nextIndex);
            currentQuestionRecordingStart = Date.now();
            startQuestionTimer();
        }
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

    // 5問分の録画をまとめてアップロード
    async function uploadBatchQuestions() {
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

        // 5問バッチの開始と終了質問番号を計算
        const batchNumber = Math.floor(currentQuestionIndex / 5) + 1;
        const batchStartQuestion = Math.floor(currentQuestionIndex / 5) * 5 + 1;
        const batchEndQuestion = Math.min(batchStartQuestion + 4, totalQuestions);

        console.log(`バッチ${batchNumber}: 質問${batchStartQuestion}-${batchEndQuestion}をアップロード`);

        // アップロード中表示を表示
        const uploadStatus = document.getElementById('upload-status');
        const uploadMessage = document.getElementById('upload-message');
        const uploadStartTime = Date.now(); // アップロード開始時刻を記録

        if (uploadStatus && uploadMessage) {
            uploadMessage.textContent = `質問${batchStartQuestion}-${batchEndQuestion}の動画をアップロード中...`;
            uploadStatus.style.display = 'flex';
        }

        // FormDataを作成
        const formData = new FormData();
        formData.append('video', blob, `interview_batch_${batchNumber}.${extension}`);
        formData.append('batch_number', batchNumber);
        formData.append('start_question', batchStartQuestion);
        formData.append('end_question', batchEndQuestion);
        formData.append('question_number', questionNumber); // 現在の質問番号（最後の質問）
        formData.append('total_questions', totalQuestions);
        formData.append('token', token);
        formData.append('_token', '{{ csrf_token() }}');

        try {
            // サーバーにアップロード
            const response = await fetch('{{ route("record.upload") }}', {
                method: 'POST',
                body: formData
            });

            console.log('サーバーレスポンス受信:', response.status, response.statusText);
            console.log('レスポンスヘッダー:', {
                contentType: response.headers.get('content-type'),
                contentLength: response.headers.get('content-length')
            });

            // レスポンスがJSONでない場合のエラーハンドリング
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const responseText = await response.text();
                console.error('JSONでないレスポンスを受信:', responseText.substring(0, 500));
                throw new Error('サーバーから不正なレスポンスが返されました（JSON形式ではありません）');
            }

            const data = await response.json();
            console.log('JSONパース成功:', data);

            // 最低2秒間は表示を維持
            const elapsedTime = Date.now() - uploadStartTime;
            const remainingTime = Math.max(0, 2000 - elapsedTime);

            if (remainingTime > 0) {
                await new Promise(resolve => setTimeout(resolve, remainingTime));
            }

            if (data.success) {
                // 完了表示に切り替え
                if (uploadStatus && uploadMessage) {
                    uploadStatus.classList.add('completed');

                    const checkMark = uploadStatus.querySelector('.upload-check');

                    // バッチ番号が1〜3の場合はキャラクター画像、4以降はチェックマーク
                    if (batchNumber <= 3) {
                        // チェックマークを非表示にする（画像を表示するため）
                        if (checkMark) checkMark.style.display = 'none';

                        // キャラクター画像を表示
                        const charaImgUrl = `{{ asset('assets/user/img/Chara-') }}${batchNumber}.png`;
                        uploadMessage.innerHTML = `<img src="${charaImgUrl}" alt="送信完了" style="width: 30rem; height: auto; display: block; margin: 0 auto;">`;
                    } else {
                        // 4回目以降はチェックマークと「完了！」テキストを表示
                        if (checkMark) checkMark.style.display = 'block';
                        uploadMessage.textContent = '完了！';
                    }

                    // 2秒後にフェードアウト（画像を確認できるよう少し長めに）
                    await new Promise(resolve => setTimeout(resolve, 2000));

                    uploadStatus.style.display = 'none';
                    uploadStatus.classList.remove('completed');

                    // 表示をリセット
                    if (checkMark) checkMark.style.display = ''; // インラインスタイルを削除してクラス制御に戻す
                    uploadMessage.textContent = '次の質問を準備中...';
                }
                console.log(`質問${questionNumber}の動画アップロード成功:`, data.file_path);
                console.log(`質問${questionNumber}のvideo_url:`, data.video_url);
                console.log('サーバーレスポンス:', data);
                uploadedCount++;

                // アップロードされた動画情報を保存（5問分の情報を含む）
                // batchNumber, batchStartQuestion, batchEndQuestion は既に定義済みなので再定義不要

                // このバッチに含まれる質問を収集
                const batchQuestions = [];
                for (let i = batchStartQuestion - 1; i < batchEndQuestion; i++) {
                    console.log(`質問${i + 1}の情報:`, questions[i]);
                    batchQuestions.push({
                        question_number: i + 1,
                        question_text: questions[i].q || questions[i].question || questions[i].question_text
                    });
                }
                console.log('batchQuestions:', batchQuestions);

                uploadedVideos.push({
                    batch_number: batchNumber,
                    start_question: batchStartQuestion,
                    end_question: batchEndQuestion,
                    video_url: data.video_url,
                    file_path: data.file_path,
                    questions: batchQuestions
                });
                console.log('uploadedVideos配列:', uploadedVideos);

                // メモリ解放
                recordedChunks = [];

                // 次の質問または完了処理
                if (currentQuestionIndex + 1 < totalQuestions) {
                    // MediaRecorderを再開
                    if (mediaRecorder && mediaRecorder.state === 'inactive') {
                        mediaRecorder.start(1000);
                    }

                    // 次の質問へ
                    moveToNextQuestion();
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

            // エラー詳細をログ出力
            console.error(`質問${questionNumber}の動画アップロードエラー:`, error);
            console.error('エラータイプ:', error.constructor.name);
            console.error('エラーメッセージ:', error.message);
            console.error('エラースタック:', error.stack);

            // より詳細なエラー情報をユーザーに表示
            let errorMessage = '動画のアップロード中にエラーが発生しました\n\n';
            errorMessage += 'エラー詳細: ' + error.message + '\n';
            errorMessage += 'エラータイプ: ' + error.constructor.name + '\n\n';
            errorMessage += 'もう一度お試しください。';

            alert(errorMessage);
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

        // プレビュー画面を表示
        showPreviewScreen();
    }

    let currentPreviewIndex = 0;
    let currentSubtitleIndex = 0; // 現在表示中の字幕インデックス

    // プレビュー画面を表示
    function showPreviewScreen() {
        console.log('プレビュー画面を表示します');
        console.log('アップロード済みバッチ数:', uploadedVideos.length);
        console.log('アップロード済み動画情報:', uploadedVideos);

        const previewScreen = document.getElementById('preview-screen');
        currentPreviewIndex = 0;
        currentSubtitleIndex = 0;

        // プレビュー画面を先に表示
        previewScreen.classList.add('active');
        console.log('プレビュー画面のクラス:', previewScreen.className);
        console.log('プレビュー画面の表示状態:', window.getComputedStyle(previewScreen).display);

        // DOMが更新されるのを待ってから動画を表示
        setTimeout(() => {
            updatePreviewDisplay();
        }, 100);
    }

    // プレビュー表示を更新
    function updatePreviewDisplay() {
        const batchInfo = uploadedVideos[currentPreviewIndex];
        console.log('プレビュー表示を更新:', currentPreviewIndex, batchInfo);
        console.log('batchInfo.questions:', batchInfo.questions);

        currentSubtitleIndex = 0;

        // questionsプロパティが存在するか確認
        if (!batchInfo.questions || batchInfo.questions.length === 0) {
            console.error('batchInfo.questionsが存在しないか空です');
            console.error('batchInfo:', batchInfo);
            return;
        }

        // 最初の質問情報を表示
        const firstQuestion = batchInfo.questions[0];
        console.log('firstQuestion:', firstQuestion);

        // 要素の存在確認
        const questionLabelElement = document.getElementById('preview-question-label');
        const questionTextElement = document.getElementById('preview-question-text');

        console.log('preview-question-label要素:', questionLabelElement);
        console.log('preview-question-text要素:', questionTextElement);

        if (questionLabelElement) {
            questionLabelElement.textContent = `Q${firstQuestion.question_number}`;
        } else {
            console.error('preview-question-label要素が見つかりません');
        }

        if (questionTextElement) {
            questionTextElement.textContent = firstQuestion.question_text;
        } else {
            console.error('preview-question-text要素が見つかりません');
        }

        // 動画を更新
        const videoElement = document.getElementById('preview-video');
        console.log('動画要素にsrcを設定:', batchInfo.video_url);

        // 既存のイベントリスナーを削除
        videoElement.removeEventListener('timeupdate', handleTimeUpdate);
        videoElement.removeEventListener('ended', handleVideoEnded);

        videoElement.src = batchInfo.video_url;
        videoElement.load();
        console.log('動画要素のsrc:', videoElement.src);

        // 動画のロードエラーをハンドリング
        videoElement.onerror = function(e) {
            console.error('動画ロードエラー:', e);
            console.error('動画要素の状態:', {
                error: videoElement.error,
                errorCode: videoElement.error ? videoElement.error.code : null,
                errorMessage: videoElement.error ? videoElement.error.message : null,
                networkState: videoElement.networkState,
                readyState: videoElement.readyState,
                currentSrc: videoElement.currentSrc
            });

            // エラーコードの意味を表示
            if (videoElement.error) {
                const errorMessages = {
                    1: 'MEDIA_ERR_ABORTED: ユーザーによる中断',
                    2: 'MEDIA_ERR_NETWORK: ネットワークエラー',
                    3: 'MEDIA_ERR_DECODE: デコードエラー',
                    4: 'MEDIA_ERR_SRC_NOT_SUPPORTED: フォーマットがサポートされていない'
                };
                console.error('エラー詳細:', errorMessages[videoElement.error.code] || '不明なエラー');
            }
        };

        // 動画のロード成功を確認
        videoElement.onloadeddata = function() {
            console.log('動画ロード成功、再生可能です');
        };

        videoElement.oncanplay = function() {
            console.log('動画の再生準備完了');
            // 自動再生はせず、ユーザーが再生ボタンを押すまで待機
            // videoElement.play().catch(err => {
            //     console.error('自動再生エラー:', err);
            // });
        };

        // 動画再生中に字幕を更新
        videoElement.addEventListener('timeupdate', handleTimeUpdate);
        videoElement.addEventListener('ended', handleVideoEnded);

        // 送信ボタンは常に表示
        const submitBtn = document.getElementById('preview-submit-btn');
        submitBtn.style.display = 'block';

        // プレビュー動画のカスタムコントロールを初期化
        initPreviewVideoControls();
    }

    // プレビュー動画のカスタムコントロールを初期化
    function initPreviewVideoControls() {
        const video = document.getElementById('preview-video');
        const playBtn = document.querySelector('.preview-play-pause-btn');
        const volumeSlider = document.querySelector('.preview-volume-slider');
        const volumeIcon = document.querySelector('.preview-volume-icon');

        if (!video || !playBtn || !volumeSlider || !volumeIcon) return;

        // 再生・一時停止
        playBtn.addEventListener('click', () => {
            if (video.paused) {
                video.play();
            } else {
                video.pause();
            }
        });

        // ビデオの状態に応じてボタン表示を更新
        video.addEventListener('play', () => {
            playBtn.textContent = '❚❚';
        });

        video.addEventListener('pause', () => {
            playBtn.textContent = '▶';
        });

        video.addEventListener('ended', () => {
            playBtn.textContent = '▶';
        });

        // 音量調整
        volumeSlider.addEventListener('input', (e) => {
            video.volume = e.target.value;
            updatePreviewVolumeIcon(e.target.value);
        });

        function updatePreviewVolumeIcon(vol) {
            if (vol == 0) {
                volumeIcon.textContent = '🔇';
            } else if (vol < 0.5) {
                volumeIcon.textContent = '🔉';
            } else {
                volumeIcon.textContent = '🔊';
            }
        }
    }

    // 動画再生中に字幕を更新（6秒ごと）
    function handleTimeUpdate(event) {
        const videoElement = event.target;
        const currentTime = videoElement.currentTime;
        const batchInfo = uploadedVideos[currentPreviewIndex];

        // 6秒ごとに質問を切り替え
        const newSubtitleIndex = Math.floor(currentTime / 6);

        if (newSubtitleIndex !== currentSubtitleIndex && newSubtitleIndex < batchInfo.questions.length) {
            currentSubtitleIndex = newSubtitleIndex;
            const question = batchInfo.questions[currentSubtitleIndex];
            document.getElementById('preview-question-label').textContent = `Q${question.question_number}`;
            document.getElementById('preview-question-text').textContent = question.question_text;
            console.log(`字幕更新: Q${question.question_number}`);
        }
    }

    // 動画再生終了時の処理
    function handleVideoEnded() {
        console.log('動画再生終了');
        // 次のバッチがあれば自動的に進む
        if (currentPreviewIndex < uploadedVideos.length - 1) {
            navigatePreview();
        }
    }

    // プレビューのナビゲーション
    function navigatePreview() {
        if (currentPreviewIndex < uploadedVideos.length - 1) {
            currentPreviewIndex++;
            updatePreviewDisplay();
        }
    }

    // 送信確認モーダルを表示
    function showSubmitConfirmModal() {
        // ボタンが既に無効化されている場合は何もしない
        const submitBtn = document.getElementById('preview-submit-btn');
        if (submitBtn.disabled) return;

        document.getElementById('submit-confirm-modal').style.display = 'flex';
    }

    // 送信確認モーダルを非表示
    function hideSubmitConfirmModal() {
        document.getElementById('submit-confirm-modal').style.display = 'none';
        console.log('ユーザーがキャンセルしました');
    }

    // 録り直し確認モーダルを表示
    function showRetakeConfirmModal() {
        document.getElementById('retake-confirm-modal').style.display = 'flex';
    }

    // 録り直し確認モーダルを非表示
    function hideRetakeConfirmModal() {
        document.getElementById('retake-confirm-modal').style.display = 'none';
        console.log('録り直しをキャンセルしました');
    }

    // 途中やり直し確認モーダルを表示
    function showInterruptConfirmModal() {
        document.getElementById('interrupt-confirm-modal').style.display = 'flex';
    }

    // 途中やり直し確認モーダルを非表示
    function hideInterruptConfirmModal() {
        document.getElementById('interrupt-confirm-modal').style.display = 'none';
        console.log('途中やり直しをキャンセルしました');
    }

    // プレビュー画面から提出
    async function submitFromPreview() {
        // モーダルを閉じる
        hideSubmitConfirmModal();

        console.log('送信ボタンがクリックされました');

        // ボタンを無効化（多重送信防止）
        const submitBtn = document.getElementById('preview-submit-btn');
        if (submitBtn.disabled) {
            console.log('既に送信処理中です');
            return;
        }

        /* confirmはモーダルで代替したため削除
        if (!confirm('この内容で送信してもよろしいですか？')) {
            console.log('ユーザーがキャンセルしました');
            return;
        }
        */

        console.log('送信処理を開始します');

        // ボタンを無効化してローディング表示
        submitBtn.disabled = true;
        submitBtn.classList.add('disabled-btn');
        submitBtn.style.opacity = '0.5';
        submitBtn.style.cursor = 'not-allowed';
        const originalText = submitBtn.textContent;
        submitBtn.textContent = '送信中...';

        // ローディング表示
        const uploadStatus = document.getElementById('upload-status');
        const uploadMessage = document.getElementById('upload-message');
        if (uploadStatus && uploadMessage) {
            uploadMessage.textContent = '送信中...';
            uploadStatus.style.display = 'flex';
        }

        try {
            const formData = new FormData();
            formData.append('token', token);
            formData.append('_token', '{{ csrf_token() }}');

            console.log('送信URL:', '{{ route("record.submitInterview") }}');
            console.log('送信データ:', { token: token });

            const response = await fetch('{{ route("record.submitInterview") }}', {
                method: 'POST',
                body: formData
            });

            console.log('レスポンス受信:', response.status, response.statusText);

            // エラーレスポンスの場合
            if (!response.ok) {
                const errorText = await response.text();
                console.error('サーバーエラー:', errorText);
                throw new Error(`サーバーエラー (${response.status}): ${response.statusText}`);
            }

            const result = await response.json();
            console.log('レスポンスJSON:', result);

            if (result.success) {
                console.log('送信成功、完了ページへ遷移します');

                // 成功表示
                if (uploadStatus && uploadMessage) {
                    uploadStatus.classList.add('completed');
                    uploadMessage.textContent = '送信完了！';
                    await new Promise(resolve => setTimeout(resolve, 1000));
                }

                // 完了ページへ遷移
                window.location.href = "{{ route('record.complete') }}?token=" + token;
            } else {
                console.error('送信失敗:', result.message);
                if (uploadStatus) uploadStatus.style.display = 'none';
                alert(result.message || '送信に失敗しました。');

                // ボタンを再有効化
                submitBtn.disabled = false;
                submitBtn.classList.remove('disabled-btn');
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
                submitBtn.textContent = originalText;
            }
        } catch (error) {
            console.error('送信エラー:', error);
            if (uploadStatus) uploadStatus.style.display = 'none';
            alert('送信中にエラーが発生しました\n' + error.message);

            // ボタンを再有効化
            submitBtn.disabled = false;
            submitBtn.classList.remove('disabled-btn');
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
            submitBtn.textContent = originalText;
        }
    }

    // プレビュー画面から録り直し（モーダル表示）
    function retakeFromPreview() {
        showRetakeConfirmModal();
        return false;
    }

    // 録り直しを実行
    async function executeRetake() {
        // モーダルを閉じる
        hideRetakeConfirmModal();

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
                // ページをリロードして最初から開始
                window.location.href = "{{ route('record.interview') }}?token=" + token + "&t=" + new Date().getTime();
            } else {
                alert(result.message || 'やり直しの開始に失敗しました。');
            }
        } catch (error) {
            console.error('やり直しエラー:', error);
            alert('通信エラーが発生しましたもう一度お試しください。');
        }
    }

    // 途中やり直しを実行
    async function executeInterrupt() {
        // モーダルを閉じる
        hideInterruptConfirmModal();

        // ボタンを無効化
        const interruptBtn = document.getElementById('interrupt-btn');
        if (interruptBtn) {
            interruptBtn.classList.add('disabled-btn');
            interruptBtn.style.pointerEvents = 'none';
        }

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
            alert('通信エラーが発生しましたもう一度お試しください。');
        }
    }

    // ページ読み込み時にカメラ起動またはプレビュー表示
    window.addEventListener('DOMContentLoaded', () => {
        // サーバーから渡されたアップロード済み動画データをチェック
        const serverUploadedVideos = @json($uploadedVideos ?? []);

        console.log('=== ページ読み込み ===');
        console.log('serverUploadedVideos:', serverUploadedVideos);
        console.log('serverUploadedVideos.length:', serverUploadedVideos.length);

        if (serverUploadedVideos.length > 0) {
            // アップロード済みの動画がある場合はプレビュー画面を表示
            console.log('アップロード済み動画を検出、プレビュー画面を表示します');
            uploadedVideos = serverUploadedVideos;
            showPreviewScreen();
        } else {
            // 新規録画の場合はカメラを起動
            console.log('新規録画、カメラを起動します');
            startCamera();
        }
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
            interruptBtn.addEventListener('click', (e) => {
                e.preventDefault();
                showInterruptConfirmModal();
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
                    <video id="interview-video" autoplay playsinline muted webkit-playsinline></video>

                    <!-- アップロード中表示 -->
                    <div id="upload-status" style="display: none;">
                        <div class="upload-statusinner">
                            <div class="loading-spinner"></div>
                            <div class="upload-check"></div>
                            <p>
                                <span id="upload-message">次の質問を準備中...</span>
                            </p>
                        </div>
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

<!-- プレビュー画面 -->
<div id="preview-screen">
    <header class="header">
        <div class="header__container">
            <img src="{{ asset('assets/user/img/logo2.png') }}" alt="らくらくセルフ面接">
        </div>
    </header>

    <main class="main">
        <div class="main__container">
            <div class="instruction instruction__interview bg-frame">
                <div class="instruction__confirm-inner">
                    <div class="preview-question-info" id="preview-question-info">
                        <div class="preview-question-label" id="preview-question-label">Q1</div>
                        <div class="preview-question-text" id="preview-question-text"></div>
                    </div>

                    <div class="instruction__preview-video">
                        <video id="preview-video" playsinline controlslist="nodownload nofullscreen noremoteplayback" disablePictureInPicture></video>
                        <div class="preview-custom-controls">
                            <button type="button" class="preview-play-pause-btn">▶</button>
                            <div class="preview-volume-container">
                                <span class="preview-volume-icon">🔊</span>
                                <input type="range" class="preview-volume-slider" min="0" max="1" step="0.1" value="1">
                            </div>
                        </div>
                    </div>

                    <div class="instruction__preview-btns">
                        @if(($entry->interrupt_retake_count ?? 0) < 1)
                            <a href="#" id="preview-retake-btn" class="instruction__retake-btn" onclick="showRetakeConfirmModal(); return false;">録り直し<span class="remaining-chance">（残り{{ 1 - ($entry->interrupt_retake_count ?? 0) }}回）</span></a>
                        @else
                            <a href="#" class="instruction__retake-btn disabled-btn">録り直し<span class="remaining-chance">（残り0回）</span></a>
                        @endif

                        <a href="#" id="preview-nav-btn" class="instruction__preview-btn" onclick="navigatePreview(); return false;">次の質問へ</a>
                    </div>
                </div>
            </div>

            <button id="preview-submit-btn" type="button" class="main__btn" onclick="showSubmitConfirmModal()" style="display: none;">送信する</button>
        </div>
    </main>

    <footer class="footer">
        <div class="footer__container">
            <p>ご不明点やトラブルがあれば、下記のサポートまでお気軽にご連絡ください。</p>
            <a href="mailto:support@casmen.jp">support@casmen.jp</a>
        </div>
    </footer>

    <!-- 送信確認モーダル -->
    <div id="submit-confirm-modal">
        <div class="modal-content">
            <p class="modal-message">この内容で送信しても<br>よろしいですか？</p>
            <div class="modal-btns">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="hideSubmitConfirmModal()">キャンセル</button>
                <button type="button" class="modal-btn modal-btn-ok" onclick="submitFromPreview()">OK</button>
            </div>
        </div>
    </div>

    <!-- 録り直し確認モーダル -->
    <div id="retake-confirm-modal">
        <div class="modal-content">
            <p class="modal-message">最初からやり直しますか？<br><br>※今回の録画内容は削除され、元に戻せません。<br>※やり直しは1回だけなので、<br>次の面接が最後になります。</p>
            <div class="modal-btns">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="hideRetakeConfirmModal()">キャンセル</button>
                <button type="button" class="modal-btn modal-btn-retake" onclick="executeRetake()">もう一度やり直す</button>
            </div>
        </div>
    </div>
</div>

<!-- 途中やり直し確認モーダル -->
<div id="interrupt-confirm-modal">
    <div class="modal-content">
        <p class="modal-message">最初からやり直しますか？<br><br>※今回の録画内容は削除され、元に戻せません。<br>※やり直しは1回だけなので、<br>次の面接が最後になります。</p>
        <div class="modal-btns">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="hideInterruptConfirmModal()">キャンセル</button>
            <button type="button" class="modal-btn modal-btn-interrupt" onclick="executeInterrupt()">もう一度やり直す</button>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="footer__container">
        <p>ご不明点やトラブルがあれば、下記のサポートまでお気軽にご連絡ください。</p>
        <a href="mailto:support@casmen.jp">support@casmen.jp</a>
    </div>
</footer>
</body>
@endsection
