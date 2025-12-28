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
            videoElement.setAttribute('playsinline', ''); // iOS対応

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
                const endTime = startTime + 8000; // 8秒間表示

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

        // 画面切り替え用の要素
        const videoContainer = document.querySelector('.instruction__confirm-video');
        const characterMessage = document.querySelector('.instruction__character-message');
        const confirmMessage = document.querySelector('.instruction__confirm-message');

        if (previewBtn) {
            previewBtn.addEventListener('click', (e) => {
                e.preventDefault(); // リンク遷移を防止
                if (videoElement.paused) {
                    // 再生開始（プレビューモードへ切り替え）
                    videoElement.muted = false;
                    videoElement.volume = 1.0; // 音量を最大に設定
                    videoElement.play();

                    // ボタンの表示変更
                    previewBtn.textContent = 'プレビュー中';
                    previewBtn.classList.add('disabled-btn'); // 見た目をdisabled風に

                    // 画面レイアウト変更 (confirm_preview.htmlの状態へ)
                    if (videoContainer) {
                        videoContainer.classList.remove('instruction__confirm-video');
                        videoContainer.classList.add('instruction__preview-video');
                    }
                    if (characterMessage) characterMessage.style.display = 'none';
                    if (confirmMessage) confirmMessage.style.display = 'none';

                    if (subtitleDiv) subtitleDiv.style.display = 'block';
                } else {
                    // 停止（元の画面へ戻す）
                    stopPreview();
                }
            });
        }

        // 動画が終了したら停止状態に戻す
        videoElement.addEventListener('ended', () => {
            stopPreview();
        });

        // プレビュー停止処理
        function stopPreview() {
            videoElement.pause();
            videoElement.currentTime = 0;

            // ボタンの表示変更
            if (previewBtn) {
                previewBtn.textContent = 'プレビュー ▶︎';
                previewBtn.classList.remove('disabled-btn');
            }

            // 画面レイアウト復帰
            if (videoContainer) {
                videoContainer.classList.add('instruction__confirm-video');
                videoContainer.classList.remove('instruction__preview-video');
            }
            if (characterMessage) characterMessage.style.display = ''; // CSSの初期値に戻す
            if (confirmMessage) confirmMessage.style.display = '';

            if (subtitleDiv) {
                subtitleDiv.textContent = '';
                subtitleDiv.style.display = 'none';
            }
        }
    });

    // 送信ボタンのクリックイベント
    document.addEventListener('DOMContentLoaded', () => {
        const submitBtn = document.getElementById('submit-btn');
        const questions = @json($questions ?? []);

        if (submitBtn) {
            submitBtn.addEventListener('click', async (e) => {
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
        }
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
        const retakeLink = document.getElementById('retake-btn');
        if (retakeLink && !retakeLink.classList.contains('disabled-btn')) {
            retakeLink.addEventListener('click', async (e) => {
                e.preventDefault();

                if (!confirm('録り直しをしますか？\n※現在の録画データは削除され、録り直し回数が1回消費されます。')) {
                    return;
                }

                try {
                    const formData = new FormData();
                    formData.append('token', token);
                    formData.append('_token', '{{ csrf_token() }}');

                    const response = await fetch('{{ route("record.retake") }}', {
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

                        window.location.href = "{{ route('record.interview-preview') }}?token=" + token;
                    } else {
                        alert(result.message || '録り直しの開始に失敗しました。');
                    }
                } catch (error) {
                    console.error('録り直しエラー:', error);
                    alert('通信エラーが発生しました。もう一度お試しください。');
                }
            });
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
            <div class="instruction__confirm-inner">
                <div class="instruction__confirm-video">
                    <video id="preview-recorded-video" playsinline></video>
                    <div class="instruction__character-message">
                        <div class="instruction__bubble">
                            <img src="{{ asset('assets/user/img/bubble.png') }}" class="instruction__bubble-img" alt="吹き出し">
                            <img src="{{ asset('assets/user/img/well-done.png') }}" class="instruction__bubble-text" alt="セルフ面談おつかれさまでした。">
                        </div>
                        <div class="instruction__character">
                            <img src="{{ asset('assets/user/img/bear.png') }}" class="instruction__bear-img" alt="クマのキャラクター">
                        </div>
                    </div>
                </div>

                <div class="instruction__preview-btns">
                    <!-- 録り直しボタン -->
                    @if($entry->retake_count < 1)
                        <a href="#" id="retake-btn" class="instruction__retake-btn">録り直し<span class="remaining-chance">（残り{{ 1 - $entry->retake_count }}回）</span></a>
                    @else
                        <a href="#" class="instruction__retake-btn disabled-btn">録り直し<span class="remaining-chance">（残り0回）</span></a>
                    @endif

                    <!-- プレビューボタン -->
                    <a href="#" id="preview-btn" class="instruction__preview-btn">プレビュー ▶︎</a>
                </div>
                <p class="instruction__confirm-message">
                    <span class="instruction__complete-notice">これで質問はすべて完了です。</span><br>
                    問題がなければ「送信する」を<br>タップしてください。
                </p>
            </div>
        </div>

        <button id="submit-btn" type="button" class="main__btn">送信する</button>
    </div>
</main>

<footer class="footer">
    <div class="footer__container">
        <p>ご不明点やトラブルがあれば、下記のサポートまでお気軽にご連絡ください。</p>
        <a href="mailto:support@casmen.jp">support@casmen.jp</a>
    </div>
</footer>
@endsection
