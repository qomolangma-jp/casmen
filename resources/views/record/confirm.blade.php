@extends('layouts.user')

@section('title', 'CASMEN｜らくらくセルフ面接')

@push('styles')
<style>
    /* 確認画面のビデオコンテナを事前に固定 */
    .instruction__confirm-video {
        display: flex !important;
        min-height: 37.5rem !important; /* ビデオの高さを確保 */
        align-items: flex-start !important; /* 上揃え */
        gap: 1.2rem !important; /* 要素間のスペース */
        position: relative !important; /* プレースホルダーの基準 */
    }

    /* 動画ローディングプレースホルダー */
    #video-placeholder {
        width: 21.9rem !important;
        height: 37.5rem !important;
        background-color: #000 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: #fff !important;
        font-size: 1.4rem !important;
        flex-shrink: 0 !important;
    }

    #video-placeholder.hidden {
        display: none !important;
    }

    /* 確認画面のビデオをプレビューと同じスタイルに */
    #preview-recorded-video {
        width: 21.9rem !important;
        height: 37.5rem !important;
        object-fit: contain !important;
        background-color: #000 !important;
        display: none !important; /* 最初は非表示 */
        flex-shrink: 0 !important; /* サイズを固定 */
    }

    #preview-recorded-video.loaded {
        display: block !important; /* ロード後に表示 */
    }

    /* モバイル縦向き録画時の回転用スタイル */
    #preview-recorded-video.rotated {
        position: absolute !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) rotate(90deg) !important;
        width: 37.5rem !important;
        height: 21.9rem !important;
    }

    /* 回転時の親コンテナ */
    .instruction__confirm-video.rotated-container {
        position: relative !important;
        width: 21.9rem !important;
        height: 37.5rem !important;
        overflow: hidden !important;
        display: block !important;
        flex-shrink: 0 !important;
    }

    /* デバッグ情報表示エリア */
    #debug-log {
        display: none !important; /* 本番環境では非表示 */
        margin-top: 1rem;
        padding: 1rem;
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1.2rem;
        font-family: monospace;
        max-height: 20rem;
        overflow-y: auto;
        white-space: pre-wrap;
        word-break: break-all;
    }

    #debug-log .log-entry {
        padding: 0.3rem 0;
        border-bottom: 1px solid #ddd;
    }

    #debug-log .log-entry:last-child {
        border-bottom: none;
    }

    #debug-log .log-error {
        color: #d32f2f;
        font-weight: bold;
    }

    #debug-log .log-success {
        color: #388e3c;
        font-weight: bold;
    }

    #debug-log .log-warn {
        color: #f57c00;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/admin/js/jquery-3.7.1.min.js') }}"></script>
<script>
    const token = "{{ $token }}";
    let recordedVideoBlob = null;
    const questions = @json($questions ?? []);
    let questionTimestamps = [];

    // デバッグログ表示関数
    function debugLog(message, type = 'info') {
        const debugDiv = document.getElementById('debug-log');
        if (!debugDiv) return;

        const timestamp = new Date().toLocaleTimeString('ja-JP');
        const entry = document.createElement('div');
        entry.className = 'log-entry';

        if (type === 'error') entry.classList.add('log-error');
        if (type === 'success') entry.classList.add('log-success');
        if (type === 'warn') entry.classList.add('log-warn');

        entry.textContent = '[' + timestamp + '] ' + message;
        debugDiv.appendChild(entry);

        // 自動スクロール
        debugDiv.scrollTop = debugDiv.scrollHeight;

        // console.logも併用
        console.log(message);
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
        debugLog('=== ページ読み込み開始 ===', 'success');

        try {
            debugLog('IndexedDBからデータ取得開始...');
            const recordedVideoBlobData = await getFromIndexedDB('recordedVideo');
            const videoToken = await getFromIndexedDB('videoToken');
            const timestampsData = await getFromIndexedDB('questionTimestamps');
            const savedMimeType = await getFromIndexedDB('videoMimeType'); // mimeTypeを取得

            if (!recordedVideoBlobData || videoToken !== token) {
                debugLog('録画データが見つかりません', 'error');
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
            debugLog('動画Blob取得: ' + recordedVideoBlob.size + ' bytes', 'success');
            debugLog('Blobタイプ: ' + recordedVideoBlob.type);
            debugLog('保存されたmimeType: ' + (savedMimeType || 'なし'));
            debugLog('Blobは有効: ' + (recordedVideoBlob instanceof Blob));

            // Blobのtypeが空の場合、保存されたmimeTypeで再作成
            if (!recordedVideoBlob.type && savedMimeType) {
                debugLog('Blobにtypeがないため、再作成します', 'warn');
                recordedVideoBlob = new Blob([recordedVideoBlob], { type: savedMimeType });
                debugLog('再作成後のBlobタイプ: ' + recordedVideoBlob.type);
            }

            // デバイス情報を先に読み込む
            const deviceInfoData = await getFromIndexedDB('deviceInfo');
            let deviceInfo = null;
            if (deviceInfoData) {
                deviceInfo = JSON.parse(deviceInfoData);
                debugLog('録画時のデバイス情報取得完了');
            }

            // 動画のメタデータ読み込み後、向きをチェック
            videoElement.addEventListener('loadedmetadata', () => {
                debugLog('loadedmetadata イベント発火', 'success');
                const width = videoElement.videoWidth;
                const height = videoElement.videoHeight;
                debugLog('録画動画の解像度: ' + width + ' x ' + height);

                // モバイルデバイスで縦向き（portrait）で録画された場合のみ回転
                const shouldRotate = deviceInfo && deviceInfo.isMobile && deviceInfo.isPortrait && width > height;

                if (shouldRotate) {
                    debugLog('モバイル縦向き録画を検出、90度回転', 'info');
                    const container = videoElement.parentElement;
                    container.classList.add('rotated-container');
                    videoElement.classList.add('rotated');
                } else {
                    debugLog('そのまま表示します', 'info');
                }

                // プレースホルダーを非表示、動画を表示
                const placeholder = document.getElementById('video-placeholder');
                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
                videoElement.classList.add('loaded');
                debugLog('動画表示完了', 'success');
            });

            // 音声トラックの確認
            videoElement.addEventListener('loadeddata', () => {
                debugLog('loadeddata イベント発火', 'success');
                const audioTracks = videoElement.audioTracks?.length || 'N/A';
                const mozAudio = videoElement.mozHasAudio || false;
                const webkitAudio = videoElement.webkitAudioDecodedByteCount || 0;
                debugLog('音声トラック数: ' + audioTracks + ', mozHasAudio: ' + mozAudio + ', webkit: ' + webkitAudio);
            });

            // エラーハンドリング
            videoElement.addEventListener('error', (e) => {
                debugLog('動画ロードエラー: ' + (videoElement.error?.message || 'Unknown error'), 'error');
            });

            // 動画ソースを設定
            videoElement.controls = false;
            videoElement.setAttribute('playsinline', ''); // iOS対応
            videoElement.preload = 'metadata'; // サムネイル表示のため
            videoElement.muted = true; // 初期状態はミュート

            debugLog('動画ソースを設定中...');
            videoElement.src = URL.createObjectURL(recordedVideoBlob);

            // 明示的にロードを開始
            videoElement.load();
            debugLog('videoElement.load() 実行完了');

            // 字幕表示のセットアップ
            setupSubtitles();

            debugLog('=== 初期化完了 ===', 'success');

        } catch (error) {
            debugLog('エラー発生: ' + error.message, 'error');
            console.error('IndexedDB読み込みエラー:', error);
            alert('録画データの読み込みに失敗しました。');
        }
    });

    // 字幕表示のセットアップ
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
                    currentSubtitle = 'Q' + (i + 1) + ': ' + timestamp.question;
                    break;
                }
            }

            subtitleDiv.textContent = currentSubtitle;
            subtitleDiv.style.display = currentSubtitle ? 'block' : 'none';
        });
    }

    // プレビューボタンのクリックイベント
    document.addEventListener('DOMContentLoaded', () => {
        debugLog('プレビューボタンのイベントリスナー登録開始');

        const previewBtn = document.getElementById('preview-btn');
        const videoElement = document.getElementById('preview-recorded-video');
        const subtitleDiv = document.getElementById('subtitle-text');

        // 画面切り替え用の要素
        const videoContainer = document.querySelector('.instruction__confirm-video');
        const characterMessage = document.querySelector('.instruction__character-message');
        const confirmMessage = document.querySelector('.instruction__confirm-message');

        debugLog('previewBtn: ' + (previewBtn ? 'OK' : 'NG'), previewBtn ? 'success' : 'error');
        debugLog('videoElement: ' + (videoElement ? 'OK' : 'NG'), videoElement ? 'success' : 'error');

        if (previewBtn) {
            previewBtn.addEventListener('click', (e) => {
                debugLog('プレビューボタンがクリックされました', 'info');
                e.preventDefault(); // リンク遷移を防止
                if (videoElement.paused) {
                    // 再生開始（プレビューモードへ切り替え）
                    debugLog('=== プレビュー再生開始 ===', 'success');
                    debugLog('現在のミュート状態: ' + videoElement.muted + ', 音量: ' + videoElement.volume);

                    // 音声トラックの確認
                    if (videoElement.mozHasAudio || videoElement.webkitAudioDecodedByteCount || videoElement.audioTracks?.length > 0) {
                        debugLog('✓ 音声トラックが存在します', 'success');
                    } else {
                        debugLog('⚠ 音声トラックが見つかりません', 'warn');
                    }

                    // iOS Safari対応: 音声を有効化してすぐに再生（ユーザーインタラクションの直接性を保つ）
                    videoElement.muted = false;
                    videoElement.volume = 1.0; // 音量を最大に設定
                    debugLog('音声を有効化 (muted=false, volume=1.0)');

                    // iOS Safariでは、ユーザーアクションから直接play()を呼ぶ必要がある
                    const playPromise = videoElement.play();

                    if (playPromise !== undefined) {
                        playPromise.then(() => {
                            debugLog('OK 再生開始成功', 'success');
                            debugLog('再生後のミュート状態: ' + videoElement.muted + ', 音量: ' + videoElement.volume);

                            // 1秒後と3秒後に音声状態を確認
                            setTimeout(() => {
                                debugLog('[1秒後] muted: ' + videoElement.muted + ', volume: ' + videoElement.volume, 'info');
                            }, 1000);
                            setTimeout(() => {
                                debugLog('[3秒後] muted: ' + videoElement.muted + ', volume: ' + videoElement.volume, 'info');
                            }, 3000);
                        }).catch(err => {
                            debugLog('NG 再生エラー: ' + err.message, 'error');
                            // エラーの場合、ミュートで再生を試みる
                            videoElement.muted = true;
                            videoElement.play().then(() => {
                                debugLog('ミュート状態で再生開始', 'warn');
                                alert('音声付き再生に失敗しました。ミュート状態で再生されます。');
                            });
                        });
                    }

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
            debugLog('=== プレビュー停止 ===', 'info');
            videoElement.pause();
            videoElement.currentTime = 0;
            // 注意: ミュートに戻さない（次回再生時のため）

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
                const message = 'Q' + (currentLoadingIndex + 1) + ': ' + questions[currentLoadingIndex].q;
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
        debugLog('録り直しボタンのイベントリスナー登録開始');

        const retakeLink = document.getElementById('retake-btn');
        debugLog('retakeLink: ' + (retakeLink ? 'OK' : 'NG'), retakeLink ? 'success' : 'error');

        if (retakeLink && !retakeLink.classList.contains('disabled-btn')) {
            debugLog('録り直しボタンにイベントリスナーを登録', 'success');
            retakeLink.addEventListener('click', async (e) => {
                debugLog('録り直しボタンがクリックされました', 'info');
                e.preventDefault();

                if (!confirm('録り直しをしますか？\n※現在の録画データは削除され、録り直し回数が1回消費されます。')) {
                    debugLog('録り直しをキャンセルしました');
                    return;
                }

                debugLog('録り直し処理を開始...');

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
                    <!-- 動画ロード中のプレースホルダー -->
                    <div id="video-placeholder">
                        <span>動画を読み込み中...</span>
                    </div>
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

                <!-- デバッグ情報表示エリア -->
                <div id="debug-log"></div>

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
