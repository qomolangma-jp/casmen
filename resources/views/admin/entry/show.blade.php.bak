<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('応募者詳細') }}
        </h2>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&display=swap');

        * {
            font-family: 'Noto Sans JP', 'Yu Gothic Medium', '游ゴシック Medium', 'Yu Gothic', '游ゴシック', 'Meiryo', 'メイリオ', sans-serif;
        }

        .status-waiting { background-color: #FEF3C7; color: #92400E; }
        .status-completed { background-color: #D1FAE5; color: #065F46; }
        .status-rejected { background-color: #FEE2E2; color: #991B1B; }
        .status-passed { background-color: #DBEAFE; color: #1E40AF; }

        /* 動画内テキストの表示改善 */
        video {
            font-family: 'Noto Sans JP', 'Yu Gothic Medium', '游ゴシック Medium', 'Yu Gothic', '游ゴシック', 'Meiryo', 'メイリオ', sans-serif;
        }

        /* 字幕スタイル - 上から90%の位置に固定 */
        video::cue {
            font-family: 'Noto Sans JP', sans-serif;
            font-size: 24px;
            color: white;
            background-color: rgba(0, 0, 0, 0.85);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.9);
            padding: 10px 20px;
            line-height: 1.4;
        }

        /* 動画コンテナの字幕位置を上から90%に固定 */
        video::-webkit-media-text-track-container {
            position: absolute;
            top: 90% !important;
            bottom: auto !important;
            left: 50%;
            transform: translate(-50%, -100%);
            width: 90%;
            text-align: center;
        }

        video::cue-region {
            position: absolute;
            top: 90% !important;
        }
    </style>

    <!-- パンくずナビ -->
    <nav class="bg-blue-50 px-4 py-3">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center space-x-2 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">TOP</a>
                <span class="text-gray-400">></span>
                <a href="{{ route('admin.entry.index') }}" class="text-blue-600 hover:text-blue-800">応募者一覧</a>
                <span class="text-gray-400">></span>
                <span class="text-gray-600">応募者詳細</span>
            </div>
        </div>
    </nav>

    <!-- メインコンテンツ -->
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

        <!-- 評価エリア -->
        <div class="bg-pink-100 border border-pink-300 rounded-lg p-4 mb-6">
            <p class="text-pink-800 font-medium">評価を行ってください</p>
        </div>

        <!-- 応募者情報カード -->
        <div class="bg-white rounded-lg shadow border p-6 mb-6">
            <!-- 基本情報 -->
            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">{{ $entry->name ?? '今田美桜' }} ({{ $entry->name_kana ?? 'いまだみお' }})</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        <span class="text-gray-700">{{ $entry->email ?? 'samplemail@casmen.jp' }}</span>
                    </div>

                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                        <span class="text-gray-700">{{ $entry->tel ?? '012-3456-7890' }}</span>
                    </div>
                </div>

                <div class="flex items-center mb-4">
                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-gray-700">動画提出: {{ $entry->completed_at ? $entry->completed_at->format('Y/m/d H:i') : '2025/09/03 11:20' }}</span>
                </div>
            </div>

            <!-- 募集職種 -->
            <div class="mb-6">
                <div class="bg-gray-100 rounded p-3">
                    <span class="text-gray-700 font-medium">Caferunキャスト（店長候補）募集</span>
                </div>
            </div>

            <!-- ステータス表示 -->
            <div class="mb-6">
                @php
                    $statusClass = match($entry->status ?? 'waiting') {
                        'completed' => 'status-completed',
                        'rejected' => 'status-rejected',
                        'passed' => 'status-passed',
                        default => 'status-waiting'
                    };

                    $statusText = match($entry->status ?? 'waiting') {
                        'completed' => '評価待ち',
                        'rejected' => '不採用',
                        'passed' => '通過',
                        default => '評価待ち'
                    };
                @endphp

                <div class="inline-block px-4 py-2 rounded-full text-sm font-medium {{ $statusClass }}">
                    {{ $statusText }}
                </div>
            </div>

            <!-- 動画プレビューエリア -->
            @if($entry->video_path)
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-3 text-gray-900">面接動画</h3>
                @php
                    $vttPath = str_replace('.webm', '.vtt', $entry->video_path);
                    $vttExists = file_exists(storage_path('app/public/' . $vttPath));
                @endphp
                <div class="bg-white rounded-lg shadow-sm border p-4 mb-4">
                    <div class="text-sm text-gray-600 mb-2">
                        <strong>動画パス:</strong> {{ $entry->video_path }}<br>
                        <strong>ファイル存在確認:</strong>
                        @if(file_exists(storage_path('app/public/' . $entry->video_path)))
                            <span class="text-green-600">✓ 存在</span> ({{ number_format(filesize(storage_path('app/public/' . $entry->video_path)) / 1024, 2) }} KB)
                        @else
                            <span class="text-red-600">✗ 存在しない</span>
                        @endif<br>
                        <strong>字幕パス:</strong> {{ $vttPath }}<br>
                        <strong>字幕存在確認:</strong>
                        @if($vttExists)
                            <span class="text-green-600">✓ 存在</span> ({{ number_format(filesize(storage_path('app/public/' . $vttPath)) / 1024, 2) }} KB)
                        @else
                            <span class="text-orange-600">✗ 存在しない</span>
                        @endif<br>
                        <strong>公開URL:</strong> <a href="{{ asset('storage/' . $entry->video_path) }}" target="_blank" class="text-blue-600 hover:underline">{{ asset('storage/' . $entry->video_path) }}</a><br>
                        <strong>カスタムURL:</strong> <a href="{{ route('record.video', ['filename' => basename($entry->video_path)]) }}" target="_blank" class="text-blue-600 hover:underline">{{ route('record.video', ['filename' => basename($entry->video_path)]) }}</a>
                    </div>
                </div>
                <div class="relative bg-gray-900 rounded-lg overflow-hidden" id="video-container">
                    <div class="bg-gray-800 flex items-center justify-center" style="min-height: 400px; position: relative;">
                        @if(file_exists(storage_path('app/public/' . $entry->video_path)))
                        <video
                            id="interview-video"
                            class="max-w-full max-h-full"
                            controls
                            preload="auto"
                            crossorigin="anonymous"
                            style="width: auto; height: auto; max-width: 100%; max-height: 500px;">
                            <source src="{{ route('record.video', ['filename' => basename($entry->video_path)]) }}" type="video/webm">
                            <source src="{{ route('record.video', ['filename' => basename($entry->video_path)]) }}" type="video/mp4">
                            @if($vttExists)
                            <track id="subtitle-track" kind="subtitles" label="日本語" srclang="ja" src="{{ asset('storage/' . $vttPath) }}">
                            @endif
                            お使いのブラウザは動画再生をサポートしていません。<br>
                            <a href="{{ route('record.video', ['filename' => basename($entry->video_path)]) }}" download class="text-blue-400 underline">動画をダウンロード</a>
                        </video>
                        <!-- カスタム字幕表示 -->
                        <div id="custom-subtitle" style="
                            position: absolute;
                            top: 90%;
                            left: 50%;
                            transform: translate(-50%, -100%);
                            background-color: rgba(0, 0, 0, 0.85);
                            color: white;
                            padding: 5px 20px;
                            font-size: 20px;
                            font-weight: bold;
                            border-radius: 8px;
                            width: 90%;
                            text-align: center;
                            z-index: 1000;
                            display: none;
                            font-family: 'Noto Sans JP', sans-serif;
                            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
                            pointer-events: none;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                        "></div>
                        @if($vttExists)
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const video = document.getElementById('interview-video');
                                const customSubtitle = document.getElementById('custom-subtitle');
                                const track = document.getElementById('subtitle-track');

                                // ネイティブ字幕を無効化
                                if (video.textTracks.length > 0) {
                                    video.textTracks[0].mode = 'hidden';
                                }

                                // VTTファイルを読み込んで解析
                                fetch('{{ asset('storage/' . $vttPath) }}')
                                    .then(response => response.text())
                                    .then(vttText => {
                                        const cues = parseVTT(vttText);
                                        console.log('字幕キュー:', cues);

                                        // 動画再生中に字幕を表示
                                        video.addEventListener('timeupdate', function() {
                                            const currentTime = video.currentTime;
                                            let currentCue = null;

                                            for (const cue of cues) {
                                                if (currentTime >= cue.start && currentTime < cue.end) {
                                                    currentCue = cue;
                                                    break;
                                                }
                                            }

                                            if (currentCue) {
                                                customSubtitle.textContent = currentCue.text;
                                                customSubtitle.style.display = 'block';
                                            } else {
                                                customSubtitle.style.display = 'none';
                                            }
                                        });
                                    })
                                    .catch(error => {
                                        console.error('VTT読み込みエラー:', error);
                                    });

                                // VTTパーサー
                                function parseVTT(vttText) {
                                    const lines = vttText.split('\n');
                                    const cues = [];
                                    let i = 0;

                                    while (i < lines.length) {
                                        const line = lines[i].trim();

                                        // タイムスタンプ行を探す
                                        if (line.includes('-->')) {
                                            const timeParts = line.split('-->');
                                            const startTime = parseVTTTime(timeParts[0].trim().split(' ')[0]);
                                            const endTime = parseVTTTime(timeParts[1].trim().split(' ')[0]);

                                            // 次の行がテキスト
                                            i++;
                                            const text = lines[i]?.trim() || '';

                                            cues.push({
                                                start: startTime,
                                                end: endTime,
                                                text: text
                                            });
                                        }
                                        i++;
                                    }

                                    return cues;
                                }

                                // VTT時間フォーマットを秒に変換
                                function parseVTTTime(timeString) {
                                    const parts = timeString.split(':');
                                    const hours = parseInt(parts[0]) || 0;
                                    const minutes = parseInt(parts[1]) || 0;
                                    const secondsParts = parts[2].split('.');
                                    const seconds = parseInt(secondsParts[0]) || 0;
                                    const milliseconds = parseInt(secondsParts[1]) || 0;

                                    return hours * 3600 + minutes * 60 + seconds + milliseconds / 1000;
                                }
                            });
                        </script>
                        @endif
                        @else
                        <div class="text-center text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-lg">動画ファイルが見つかりません</p>
                            <p class="text-sm text-gray-500">{{ $entry->video_path }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @else
            <div class="mb-6">
                <div class="bg-gray-100 rounded-lg p-8 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h6l2 2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM5 8a1 1 0 011-1h1a1 1 0 010 2H6a1 1 0 01-1-1zm6 1a1 1 0 100 2 1 1 0 000-2z"/>
                    </svg>
                    <p class="text-gray-500">動画がまだアップロードされていません</p>
                </div>
            </div>
            @endif

            <!-- アクションボタン -->
            @if($entry->status === 'rejected' || $entry->status === 'passed')
                <!-- 評価済みの場合 -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                    <div class="mb-4">
                        @if($entry->status === 'rejected')
                            <div class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-full">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                不採用
                            </div>
                        @else
                            <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                通過
                            </div>
                        @endif
                    </div>
                    <p class="text-gray-600 mb-2">この応募者の評価は完了しています</p>
                    @if($entry->decision_at)
                        <p class="text-sm text-gray-500">評価日時: {{ $entry->decision_at->format('Y年m月d日 H:i') }}</p>
                    @endif
                </div>
            @else
                <!-- 未評価の場合 -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <button
                        id="rejectBtn"
                        class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200">
                        不採用通知を送る
                    </button>

                    <button
                        id="passBtn"
                        class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200">
                        通過
                    </button>
                </div>
            @endif

            <!-- 注意書き -->
            <div class="mt-4 text-sm text-gray-600 text-center">
                ※応募者・動画ファイルは回答後30日で削除されます。
            </div>
        </div>

        <!-- 戻るリンク -->
        <div class="text-center">
            <a href="{{ route('admin.entry.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
                一覧へ戻る
            </a>
        </div>
    </div>

    <!-- 不採用確認モーダル -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg max-w-md w-full mx-4 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">本当にこの応募者を「不採用」にしますか？</h3>

            <div class="text-sm text-gray-600 mb-6 space-y-2">
                <p>※「不採用」を選ぶと、登録されているメールまたは電話番号宛に不採用通知が自動送信されます。</p>
                <p>※LINE・SNS経由で応募された方には自動通知ができません。お手数ですが店舗様より直接「不採用」のご連絡をお願いいたします。</p>
            </div>

            <div class="flex gap-4">
                <button id="cancelReject" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded">
                    キャンセル
                </button>
                <button id="confirmReject" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                    不採用
                </button>
            </div>
        </div>
    </div>

    <!-- 通過確認モーダル -->
    <div id="passModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg max-w-md w-full mx-4 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">本当にこの応募者を「通過」にしますか？</h3>

            <div class="text-sm text-gray-600 mb-6 space-y-2">
                <p>※「通過」を選ぶと、ステータスが「通過」に変更されます。</p>
                <p>※通過のご連絡は、お手数ですが店舗様より直接ご連絡をお願いいたします。</p>
            </div>

            <div class="flex gap-4">
                <button id="cancelPass" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded">
                    キャンセル
                </button>
                <button id="confirmPass" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    通過
                </button>
            </div>
        </div>
    </div>

    <script>
        // 評価済みの場合はJavaScriptを実行しない
        @if($entry->status === 'rejected' || $entry->status === 'passed')
        // 評価済みのため、JavaScript処理をスキップ
        @else
        // CSRFトークンを取得
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // モーダル要素を取得
        const rejectModal = document.getElementById('rejectModal');
        const passModal = document.getElementById('passModal');

        // ボタン要素を取得
        const rejectBtn = document.getElementById('rejectBtn');
        const passBtn = document.getElementById('passBtn');
        const cancelReject = document.getElementById('cancelReject');
        const confirmReject = document.getElementById('confirmReject');
        const cancelPass = document.getElementById('cancelPass');
        const confirmPass = document.getElementById('confirmPass');

        // 不採用ボタンクリック
        rejectBtn.addEventListener('click', () => {
            rejectModal.classList.remove('hidden');
        });

        // 通過ボタンクリック
        passBtn.addEventListener('click', () => {
            passModal.classList.remove('hidden');
        });

        // モーダルキャンセル
        cancelReject.addEventListener('click', () => {
            rejectModal.classList.add('hidden');
        });

        cancelPass.addEventListener('click', () => {
            passModal.classList.add('hidden');
        });

        // 背景クリックでモーダルを閉じる
        rejectModal.addEventListener('click', (e) => {
            if (e.target === rejectModal) {
                rejectModal.classList.add('hidden');
            }
        });

        passModal.addEventListener('click', (e) => {
            if (e.target === passModal) {
                passModal.classList.add('hidden');
            }
        });

        // 不採用確定
        confirmReject.addEventListener('click', async () => {
            try {
                const response = await fetch(`{{ route('admin.entry.reject', $entry->entry_id) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('エラーが発生しました。');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('エラーが発生しました。');
            }

            rejectModal.classList.add('hidden');
        });

        // 通過確定
        confirmPass.addEventListener('click', async () => {
            try {
                const response = await fetch(`{{ route('admin.entry.pass', $entry->entry_id) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('エラーが発生しました。');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('エラーが発生しました。');
            }

            passModal.classList.add('hidden');
        });
        @endif
    </script>

    <!-- フッター -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="text-center">
                <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">個人情報の取り扱いについて</a>
            </div>
            <div class="text-center text-gray-600 text-sm mt-2">
                Copyright© CASMEN All Rights Reserved.
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.querySelector('video');
            if (video) {
                // 動画のメタデータが読み込まれたら最初のフレームを表示
                video.addEventListener('loadedmetadata', function() {
                    // 動画の最初のフレームを表示するために少し進める
                    video.currentTime = 0.1;
                });

                // 動画データが読み込まれたら最初のフレームをキャプチャ
                video.addEventListener('loadeddata', function() {
                    video.currentTime = 0.1;
                });

                // エラーハンドリング
                video.addEventListener('error', function(e) {
                    console.error('動画読み込みエラー:', e);
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'text-center text-red-400 p-4';
                    errorDiv.innerHTML = `
                        <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <p>動画の読み込みに失敗しました</p>
                    `;
                    video.parentNode.replaceChild(errorDiv, video);
                });

                // 明示的にロード開始
                video.load();
            }
        });
    </script>
</x-app-layout>
