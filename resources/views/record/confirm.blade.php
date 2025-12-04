@extends('layouts.user')

@section('title', 'CASMEN｜らくらくセルフ面接')

@push('scripts')
<script src="{{ asset('assets/admin/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/user/js/main.js') }}"></script>
<script>
    const token = "{{ $token }}";
    let recordedVideoBlob = null;
    const questions = @json($questions ?? []);
    let questionTimestamps = [];

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

    async function getFromIndexedDB(key) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const transaction = db.transaction(storeName, 'readonly');
            const store = transaction.objectStore(storeName);
            const request = store.get(key);
            request.onsuccess = (event) => resolve(event.target.result);
            request.onerror = (event) => reject(event.target.error);
        });
    }

    // ページ読み込み時に録画データを取得して表示
    window.addEventListener('DOMContentLoaded', async () => {
        try {
            const recordedVideoBlobData = await getFromIndexedDB('recordedVideo');
            const videoToken = await getFromIndexedDB('videoToken');
            const timestampsData = await getFromIndexedDB('questionTimestamps');

            if (!recordedVideoBlobData || videoToken !== token) {
                alert('録画データが見つかりません。最初からやり直してください。');
                window.location.href = "{{ route('record.interview-preview') }}?token=" + token;
                return;
            }

            // 質問タイムスタンプを読み込み
            if (timestampsData) {
                questionTimestamps = JSON.parse(timestampsData);
                console.log('質問タイムスタンプ:', questionTimestamps);
            }

            const videoElement = document.getElementById('preview-recorded-video');
            recordedVideoBlob = recordedVideoBlobData;
            console.log('動画Blob取得:', recordedVideoBlob.size, 'bytes');

            videoElement.src = URL.createObjectURL(recordedVideoBlob);
            videoElement.controls = false;
            videoElement.muted = true;

            // 字幕表示のセットアップ
            setupSubtitles();

        } catch (error) {
            console.error('IndexedDB読み込みエラー:', error);
            alert('録画データの読み込みに失敗しました。');
        }
    });    // 字幕表示のセットアップ
    function setupSubtitles() {
        const videoElement = document.getElementById('preview-recorded-video');
        const subtitleDiv = document.getElementById('subtitle-text');

        if (!subtitleDiv || questionTimestamps.length === 0) return;

        // 動画再生中に字幕を更新
        videoElement.addEventListener('timeupdate', () => {
            const currentTime = videoElement.currentTime * 1000; // ミリ秒に変換
            let currentSubtitle = '';

            for (let i = 0; i < questionTimestamps.length; i++) {
                const timestamp = questionTimestamps[i];
                const startTime = timestamp.startTime;
                const endTime = startTime + 5000; // 5秒間表示

                if (currentTime >= startTime && currentTime < endTime) {
                    currentSubtitle = `Q${i + 1}: ${timestamp.question}`;
                    break;
                }
            }

            subtitleDiv.textContent = currentSubtitle;
            subtitleDiv.style.display = currentSubtitle ? 'block' : 'none';
        });
    }

    // プレビューボタンのクリックイベント
    document.addEventListener('DOMContentLoaded', () => {
        const previewBtn = document.getElementById('preview-btn');
        const videoElement = document.getElementById('preview-recorded-video');
        const subtitleDiv = document.getElementById('subtitle-text');

        previewBtn.addEventListener('click', () => {
            if (videoElement.paused) {
                videoElement.muted = false;
                videoElement.play();
                previewBtn.textContent = '停止 ■';
                if (subtitleDiv) subtitleDiv.style.display = 'block';
            } else {
                videoElement.pause();
                videoElement.currentTime = 0;
                videoElement.muted = true;
                previewBtn.textContent = 'プレビュー ▶︎';
                if (subtitleDiv) {
                    subtitleDiv.textContent = '';
                    subtitleDiv.style.display = 'none';
                }
            }
        });

        // 動画が終了したら停止状態に戻す
        videoElement.addEventListener('ended', () => {
            videoElement.currentTime = 0;
            videoElement.muted = true;
            previewBtn.textContent = 'プレビュー ▶︎';
            if (subtitleDiv) {
                subtitleDiv.textContent = '';
                subtitleDiv.style.display = 'none';
            }
        });
    });

    // 送信ボタンのクリックイベント
    document.addEventListener('DOMContentLoaded', () => {
        const submitForm = document.querySelector('form');
        const submitBtn = document.getElementById('submit-btn');
        const questions = @json($questions ?? []);

        submitForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            if (!recordedVideoBlob) {
                alert('録画データが見つかりません。');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = '送信中...';

            // ローディング画面を表示
            showLoadingScreen(questions);

            console.log('送信開始:', {
                blobSize: recordedVideoBlob.size,
                blobType: recordedVideoBlob.type,
                token: token
            });

            // 質問タイムスタンプを取得 (IndexedDBから)
            let questionTimestamps = [];
            try {
                const timestampsData = await getFromIndexedDB('questionTimestamps');
                if (timestampsData) {
                    questionTimestamps = JSON.parse(timestampsData);
                }
            } catch (err) {
                console.error('タイムスタンプ取得エラー:', err);
            }

            // FormDataを作成して動画データと字幕情報を送信
            const formData = new FormData();
            formData.append('token', token);
            formData.append('video', recordedVideoBlob, 'interview.webm');
            formData.append('timestamps', JSON.stringify(questionTimestamps));
            formData.append('_token', '{{ csrf_token() }}');

            console.log('FormData作成完了');

            try {
                const response = await fetch('{{ route("record.submit") }}', {
                    method: 'POST',
                    body: formData
                });

                console.log('レスポンス:', {
                    ok: response.ok,
                    status: response.status,
                    statusText: response.statusText
                });

                const result = await response.json();
                console.log('レスポンスボディ:', result);

                if (response.ok && result.success) {
                    // IndexedDBをクリア
                    try {
                        const db = await openDB();
                        const transaction = db.transaction(storeName, 'readwrite');
                        const store = transaction.objectStore(storeName);
                        store.clear();
                    } catch (err) {
                        console.error('IndexedDBクリアエラー:', err);
                    }

                    // セッションストレージも念のためクリア
                    sessionStorage.removeItem('recordedVideo');
                    sessionStorage.removeItem('videoToken');
                    sessionStorage.removeItem('questionTimestamps');

                    console.log('送信成功、完了ページへ遷移');
                    // 完了ページへ遷移
                    window.location.href = "{{ route('record.complete') }}";
                } else {
                    throw new Error(result.message || '送信に失敗しました');
                }
            } catch (error) {
                console.error('送信エラー:', error);

                // ローディング画面を削除
                const loadingOverlay = document.getElementById('loading-overlay');
                if (loadingOverlay) loadingOverlay.remove();

                alert('動画の送信に失敗しました。\nエラー: ' + error.message);
                submitBtn.disabled = false;
                submitBtn.textContent = '送信する';
            }
        });
    });

    // ローディング画面を表示
    function showLoadingScreen(questions) {
        const loadingHTML = `
            <div id="loading-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; display: flex; flex-direction: column; justify-content: center; align-items: center; color: white;">
                <div style="text-align: center;">
                    <h2>動画を処理中です...</h2>
                    <p id="loading-message" style="margin-top: 20px; font-size: 18px;">質問を確認しています</p>
                    <div style="margin-top: 30px;">
                        <div class="spinner" style="border: 4px solid #f3f3f3; border-top: 4px solid #8b5cf6; border-radius: 50%; width: 60px; height: 60px; animation: spin 1s linear infinite; margin: 0 auto;"></div>
                    </div>
                </div>
            </div>
            <style>
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
            </style>
        `;
        document.body.insertAdjacentHTML('beforeend', loadingHTML);

        // 質問を5秒ずつ表示
        let currentLoadingIndex = 0;
        const loadingInterval = setInterval(() => {
            if (currentLoadingIndex < questions.length) {
                const message = `Q${currentLoadingIndex + 1}: ${questions[currentLoadingIndex].q}`;
                document.getElementById('loading-message').textContent = message;
                currentLoadingIndex++;
            } else {
                clearInterval(loadingInterval);
                document.getElementById('loading-message').textContent = '処理を完了しています...';
            }
        }, 5000);
    }

    // 録り直しボタンのクリックイベント
    document.addEventListener('DOMContentLoaded', () => {
        const retakeLinks = document.querySelectorAll('.inline-purple-btn:not(.disabled)');
        retakeLinks.forEach(link => {
            link.addEventListener('click', async (e) => {
                // 字幕入り動画を削除
                const subtitledVideoPath = sessionStorage.getItem('subtitledVideoPath');
                if (subtitledVideoPath) {
                    try {
                        await fetch('{{ route("record.retake") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                token: token,
                                videoPath: subtitledVideoPath
                            })
                        });
                    } catch (error) {
                        console.error('動画削除エラー:', error);
                    }
                }

                // セッションストレージをクリア
                sessionStorage.removeItem('recordedVideo');
                sessionStorage.removeItem('videoToken');
                sessionStorage.removeItem('subtitledVideoPath');
                sessionStorage.removeItem('questionTimestamps');

                // 録り直し回数をインクリメント
                const retakeCount = parseInt(sessionStorage.getItem('retakeCount') || '0');
                sessionStorage.setItem('retakeCount', (retakeCount + 1).toString());
            });
        });
    });
</script>
@endpush

@section('content')
<header>
    <div class="header-container">
        <div class="header-container-inner line-logo">
            <img src="{{ asset('assets/user/img/logo2.png') }}" alt="らくらくセルフ面接">
        </div>
    </div>
</header>
<main>
    <div class="main-container">
        <div class="main-content complete-content">
            <div class="complete-description">
                <p class="complete-message">
                    <span>これで質問はすべて完了です。</span>
                    問題がなければ「送信する」を<br>タップしてください。
                </p>
            </div>
            <div class="video lg-video" style="position: relative;">
                <video id="preview-recorded-video"></video>
                <div id="subtitle-text" style="
                    display: none;
                    position: absolute;
                    bottom: 20px;
                    left: 50%;
                    transform: translateX(-50%);
                    background-color: rgba(0, 0, 0, 0.85);
                    color: white;
                    padding: 12px 24px;
                    font-size: 18px;
                    font-weight: bold;
                    border-radius: 8px;
                    max-width: 90%;
                    text-align: center;
                    z-index: 10;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    font-family: 'Noto Sans JP', sans-serif;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
                "></div>
                <div class="character-short">
                    <div class="bubble-lg">
                        <img src="{{ asset('assets/user/img/well-done.png') }}" alt="セルフ面談おつかれさまでした。">
                    </div>
                    <img src="{{ asset('assets/user/img/bear.png') }}" alt="クマのキャラクター">
                </div>
            </div>
            <div class="preview-btns">
                @if(session('retake_count', 0) < 1)
                    <a href="{{ route('record.interview-preview', ['token' => $token]) }}" class="inline-purple-btn">
                        録り直し<span class="rest">（残り１回）</span>
                    </a>
                @else
                    <a href="#" class="inline-purple-btn disabled">録り直し<span class="rest">（残り0回）</span></a>
                @endif
                <button id="preview-btn" class="inline-pink-btn">プレビュー ▶︎</button>
            </div>
        </div>
        <form action="{{ route('record.submit') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <button id="submit-btn" type="submit" class="purple-btn">送信する</button>
        </form>
    </div>
</main>
<footer>
    <div class="footer-container">
        <p>ご不明点やトラブルがあれば、下記のサポートまでお気軽にご連絡ください。</p>
        <a href="mailto:support@casmen.jp">support@casmen.jp</a>
    </div>
</footer>
@endsection
