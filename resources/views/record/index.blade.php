@extends('layouts.interview')

@section('title', 'らくらくセルフ面接')

@section('content')
<div class="bg-pink-50 min-h-screen py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(isset($errorMessage))
            <!-- エラーメッセージ -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <h3 class="text-sm font-medium text-red-800">{{ $errorMessage }}</h3>
                </div>
            </div>
        @elseif($isValidToken)
            <!-- らくらくセルフ面接メインコンテンツ -->
            <div class="text-center mb-8">
                <!-- ロゴエリア -->
                <div class="mb-6">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-pink-200 rounded-full mb-4">
                        <svg class="w-12 h-12 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-pink-600 mb-2">らくらくセルフ面接</h1>
                    <p class="text-gray-600">カンタン面接！動画で自己PR</p>
                </div>
            </div>

            <!-- プログレスステップ -->
            <div class="mb-8">
                <div class="flex justify-center items-center space-x-4">
                    <div id="step1" class="flex items-center">
                        <div class="w-8 h-8 bg-pink-500 text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                        <span class="ml-2 text-sm text-pink-600 font-medium">質問確認</span>
                    </div>
                    <div class="w-8 h-0.5 bg-gray-300"></div>
                    <div id="step2" class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold">2</div>
                        <span class="ml-2 text-sm text-gray-600">録画</span>
                    </div>
                    <div class="w-8 h-0.5 bg-gray-300"></div>
                    <div id="step3" class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold">3</div>
                        <span class="ml-2 text-sm text-gray-600">完了</span>
                    </div>
                </div>
            </div>

            <!-- メインカード -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <!-- 質問表示エリア -->
                <div id="questionStep" class="text-center">
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-pink-100 rounded-full mb-4">
                            <span class="text-2xl font-bold text-pink-600" id="questionNumber">5</span>
                        </div>
                    </div>

                    <h2 class="text-xl font-bold text-gray-800 mb-6" id="questionText">
                        最近ハマっていることは？
                    </h2>

                    <div class="mb-8">
                        <div class="inline-flex items-center justify-center w-32 h-32 bg-pink-100 rounded-full mb-4">
                            <svg class="w-16 h-16 text-pink-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                            </svg>
                        </div>
                        <p class="text-gray-600 text-sm">
                            この質問に答えて録画してください<br>
                            制限時間: 1分間
                        </p>
                    </div>

                    <button id="startInterviewBtn" class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-4 px-8 rounded-full text-lg transition duration-200">
                        面接を始める
                    </button>
                </div>

                <!-- 録画エリア -->
                <div id="recordingStep" class="text-center" style="display: none;">
                    <div class="mb-6">
                        <video id="videoPreview" width="400" height="300" class="mx-auto rounded-lg shadow-md bg-black" muted autoplay></video>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2" id="currentQuestion">
                            質問5: 最近ハマっていることは？
                        </h3>
                        <div class="flex justify-center items-center space-x-4">
                            <div id="timer" class="text-2xl font-bold text-pink-600">01:00</div>
                            <div id="recordingIndicator" class="flex items-center text-red-600" style="display: none;">
                                <div class="w-3 h-3 bg-red-600 rounded-full animate-pulse mr-2"></div>
                                <span class="text-sm font-medium">録画中</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <button id="startRecord" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-8 rounded-full text-lg transition duration-200">
                                録画開始
                            </button>
                            <button id="stopRecord" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-8 rounded-full text-lg transition duration-200 ml-4" disabled>
                                録画停止
                            </button>
                        </div>

                        <div>
                            <button id="retryRecord" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded-full transition duration-200" style="display: none;">
                                もう一度録画
                            </button>
                            <button id="nextQuestion" class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-6 rounded-full transition duration-200 ml-4" style="display: none;">
                                次の質問へ
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 完了エリア -->
                <div id="completionStep" class="text-center" style="display: none;">
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                            <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-800 mb-4">お疲れさまでした！</h2>
                    <p class="text-gray-600 mb-8">
                        面接動画の録画が完了しました。<br>
                        内容を確認してアップロードしてください。
                    </p>

                    <div class="space-y-4">
                        <button id="previewVideo" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-full transition duration-200">
                            録画内容を確認
                        </button>
                        <br>
                        <button id="uploadVideo" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-full transition duration-200">
                            面接動画をアップロード
                        </button>
                    </div>

                    <div id="uploadProgress" class="mt-4 text-center text-sm text-gray-600" style="display: none;">
                        <div class="bg-gray-200 rounded-full h-2 mb-2">
                            <div id="progressBar" class="bg-pink-500 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                        アップロード中...
                    </div>
                </div>
            </div>
        @else
            <!-- 無効なトークンの場合 -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <h3 class="text-sm font-medium text-yellow-800">面接URLが無効か期限切れです</h3>
                </div>
                <p class="mt-2 text-sm text-yellow-700">
                    有効な面接URLを確認してください。URLの有効期限が切れている可能性があります。
                </p>
            </div>
        @endif
    </div>
</div>

@if($isValidToken)
<script>
let mediaRecorder;
let recordedChunks = [];
let stream;
let currentStep = 1;
let timer;
let timeLeft = 60; // 60秒

// 質問データ
const questions = [
    { number: 1, text: "自己紹介をお願いします" },
    { number: 2, text: "志望動機を教えてください" },
    { number: 3, text: "あなたの強みは何ですか？" },
    { number: 4, text: "将来の目標を教えてください" },
    { number: 5, text: "最近ハマっていることは？" }
];

let currentQuestionIndex = 4; // 質問5から開始（配列のインデックス4）

// ステップ管理
function updateStep(step) {
    currentStep = step;

    // プログレスバーの更新
    for (let i = 1; i <= 3; i++) {
        const stepElement = document.getElementById(`step${i}`);
        const circle = stepElement.querySelector('div');
        const text = stepElement.querySelector('span');

        if (i < step) {
            // 完了済み
            circle.className = 'w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold';
            text.className = 'ml-2 text-sm text-green-600 font-medium';
        } else if (i === step) {
            // 現在のステップ
            circle.className = 'w-8 h-8 bg-pink-500 text-white rounded-full flex items-center justify-center text-sm font-bold';
            text.className = 'ml-2 text-sm text-pink-600 font-medium';
        } else {
            // 未完了
            circle.className = 'w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold';
            text.className = 'ml-2 text-sm text-gray-600';
        }
    }

    // ステップ表示の切り替え
    document.getElementById('questionStep').style.display = step === 1 ? 'block' : 'none';
    document.getElementById('recordingStep').style.display = step === 2 ? 'block' : 'none';
    document.getElementById('completionStep').style.display = step === 3 ? 'block' : 'none';
}

// タイマー機能
function startTimer() {
    timeLeft = 60;
    updateTimerDisplay();

    timer = setInterval(() => {
        timeLeft--;
        updateTimerDisplay();

        if (timeLeft <= 0) {
            clearInterval(timer);
            stopRecording();
        }
    }, 1000);
}

function updateTimerDisplay() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    document.getElementById('timer').textContent =
        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

// カメラアクセス
async function initCamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: true,
            audio: true
        });
        document.getElementById('videoPreview').srcObject = stream;
    } catch (err) {
        console.error('カメラアクセスエラー:', err);
        alert('カメラとマイクへのアクセスを許可してください。');
    }
}

// イベントリスナー設定
document.getElementById('startInterviewBtn').addEventListener('click', () => {
    updateStep(2);
    initCamera();
});

// 録画開始
function startRecording() {
    recordedChunks = [];

    mediaRecorder = new MediaRecorder(stream, {
        mimeType: 'video/webm;codecs=vp9'
    });

    mediaRecorder.ondataavailable = (event) => {
        if (event.data.size > 0) {
            recordedChunks.push(event.data);
        }
    };

    mediaRecorder.onstop = () => {
        clearInterval(timer);
        document.getElementById('recordingIndicator').style.display = 'none';
        document.getElementById('startRecord').disabled = false;
        document.getElementById('stopRecord').disabled = true;
        document.getElementById('retryRecord').style.display = 'inline-block';
        document.getElementById('nextQuestion').style.display = 'inline-block';
    };

    mediaRecorder.start();
    startTimer();

    document.getElementById('startRecord').disabled = true;
    document.getElementById('stopRecord').disabled = false;
    document.getElementById('recordingIndicator').style.display = 'flex';
}

// 録画停止
function stopRecording() {
    if (mediaRecorder && mediaRecorder.state === 'recording') {
        mediaRecorder.stop();
    }
}

// 録画開始ボタン
document.getElementById('startRecord').addEventListener('click', startRecording);

// 録画停止ボタン
document.getElementById('stopRecord').addEventListener('click', stopRecording);

// もう一度録画
document.getElementById('retryRecord').addEventListener('click', () => {
    document.getElementById('retryRecord').style.display = 'none';
    document.getElementById('nextQuestion').style.display = 'none';
    timeLeft = 60;
    updateTimerDisplay();
});

// 次の質問へ / 完了へ
document.getElementById('nextQuestion').addEventListener('click', () => {
    updateStep(3);
});

// 動画プレビュー
document.getElementById('previewVideo').addEventListener('click', () => {
    const blob = new Blob(recordedChunks, { type: 'video/webm' });
    const url = URL.createObjectURL(blob);

    // モーダルまたは新しいウィンドウでプレビュー表示
    const previewWindow = window.open('', '_blank');
    previewWindow.document.write(`
        <html>
            <head><title>録画プレビュー</title></head>
            <body style="margin:0; display:flex; justify-content:center; align-items:center; min-height:100vh; background:#000;">
                <video controls autoplay style="max-width:100%; max-height:100%;">
                    <source src="${url}" type="video/webm">
                </video>
            </body>
        </html>
    `);
});

// 動画アップロード
document.getElementById('uploadVideo').addEventListener('click', () => {
    const blob = new Blob(recordedChunks, { type: 'video/webm' });
    uploadVideo(blob);
});

function uploadVideo(blob) {
    const formData = new FormData();
    formData.append('video', blob, 'interview_video.webm');
    formData.append('token', '{{ $token }}');
    formData.append('_token', '{{ csrf_token() }}');

    document.getElementById('uploadProgress').style.display = 'block';
    document.getElementById('uploadVideo').disabled = true;

    // プログレスバーのアニメーション
    let progress = 0;
    const progressInterval = setInterval(() => {
        progress += Math.random() * 10;
        if (progress > 90) progress = 90;
        document.getElementById('progressBar').style.width = progress + '%';
    }, 100);

    fetch('{{ route("record.upload") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        clearInterval(progressInterval);
        document.getElementById('progressBar').style.width = '100%';

        setTimeout(() => {
            if (data.success) {
                alert('面接動画がアップロードされました！');
                window.location.href = '{{ route("record.complete") }}';
            } else {
                alert('アップロードに失敗しました: ' + data.message);
            }
        }, 500);
    })
    .catch(error => {
        clearInterval(progressInterval);
        console.error('アップロードエラー:', error);
        alert('アップロードに失敗しました。');
    })
    .finally(() => {
        document.getElementById('uploadProgress').style.display = 'none';
        document.getElementById('uploadVideo').disabled = false;
    });
}

// ページ初期化
window.addEventListener('load', () => {
    updateStep(1);

    // 質問表示の初期化
    const currentQuestion = questions[currentQuestionIndex];
    document.getElementById('questionNumber').textContent = currentQuestion.number;
    document.getElementById('questionText').textContent = currentQuestion.text;
    document.getElementById('currentQuestion').textContent =
        `質問${currentQuestion.number}: ${currentQuestion.text}`;
});
</script>
@endif
@endsection
