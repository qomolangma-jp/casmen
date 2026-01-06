@extends('layouts.admin')

@section('title', 'CASMEN｜応募者詳細')

@push('styles')
<style>
    /* カスタム字幕スタイル */
    .custom-subtitle {
        position: absolute;
        bottom: 60px;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 12px 20px;
        text-align: center;
<<<<<<< HEAD
        font-size: 16px;
        line-height: 1.5;
=======
        width: 90%;
        display: block;
>>>>>>> 0c63ab33bf4160433b68b530c2ffab7cdc11506d
        z-index: 10;
    }

    /* 質問切り替えボタン */
    .question-nav {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        margin: 15px 0;
        padding: 10px;
        background-color: #f5f5f5;
        border-radius: 8px;
    }
    .question-nav button {
        background-color: #4a5568;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
<<<<<<< HEAD
=======
        white-space: normal;
        word-wrap: break-word;
        line-height: 1.5;
    }

    /* 質問切り替えボタン */
    .question-nav {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        margin: 15px 0;
        padding: 10px;
        background-color: #f5f5f5;
        border-radius: 8px;
    }
    .question-nav button {
        background-color: #4a5568;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
>>>>>>> 0c63ab33bf4160433b68b530c2ffab7cdc11506d
        cursor: pointer;
        font-size: 14px;
    }
    .question-nav button:hover:not(:disabled) {
        background-color: #2d3748;
    }
    .question-nav button:disabled {
        background-color: #cbd5e0;
        cursor: not-allowed;
    }
    .question-info {
        font-weight: bold;
        color: #2d3748;
    }
    .video-container {
        position: relative;
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
    }
    /* モーダル初期非表示 */
    .modal {
        display: none;
    }
    /* コピー完了メッセージ */
    .copy-message {
        position: absolute;
        top: -35px;
        right: 0;
        background: #333;
        color: #fff;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        display: none;
        white-space: nowrap;
    }
    .copy-message::after {
        content: "";
        position: absolute;
        bottom: -5px;
        right: 15px;
        border-width: 5px 5px 0;
        border-style: solid;
        border-color: #333 transparent transparent transparent;
    }
    .copy-url {
        position: relative;
    }
    /* コピーボタンのスタイル復元 */
    #copy-btn {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #333;
        width: 6rem;
        border: .2rem solid #ddd;
        border-top-right-radius: .5rem;
        border-bottom-right-radius: .5rem;
        cursor: pointer;
    }
    #copy-btn:hover {
        background-color: #999;
    }
    /* カスタムコントロール */
    .custom-controls {
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: rgba(0, 0, 0, 0.7);
        padding: 8px 15px;
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
        box-sizing: border-box;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
    }
    .play-pause-btn {
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
    .play-pause-btn:hover {
        color: #ddd;
    }
    .volume-container {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .volume-icon {
        color: white;
        font-size: 16px;
        width: 20px;
        text-align: center;
    }
    .volume-slider {
        width: 80px;
        cursor: pointer;
    }
    /* ビデオコンテナの調整 */
    .video-container, .video-wrapper {
        position: relative;
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
    }
    video {
        display: block; /* 下部の隙間除去 */
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
    }
</style>
@endpush

@section('content')
<main>
    <div class="main-container">
        <div class="breadcrumbs">
            <span><a href="{{ route('admin.dashboard') }}">TOP</a> > <a href="{{ route('admin.entry.index') }}">応募者一覧</a> > 応募者詳細</span>
        </div>
        <div class="ray-content applicant-content">
            <h2>応募者詳細</h2>
            <div class="applicant-detail">
                <div class="user-status">
                    @if($entry->status === 'completed' && empty($entry->decision_at))
                    <div class="status-label">
                        <span class="review-request">評価を行ってください</span>
                        <span class="label waiting-review">評価待ち</span>
                    </div>
                    @endif
                    <span>{{ $entry->name }}@if($entry->name_kana)（{{ $entry->name_kana }}）@endif</span>
                </div>
                <ul class="applicant-contact">
                    <li>
                        <span><img src="{{ asset('assets/admin/img/email-icon-gray.png') }}" alt="メールアイコン"></span>
                        <span>{{ $entry->email ?? '登録なし' }}</span>
                    </li>
                    <li>
                        <span><img src="{{ asset('assets/admin/img/tel-icon.png') }}" alt="TELアイコン"></span>
                        <span>{{ $entry->tel ?? '登録なし' }}</span>
                    </li>
                    @if($entry->completed_at)
                    <li>
                        <span><img src="{{ asset('assets/admin/img/movie-icon.png') }}" alt="動画アイコン"></span>
                        <span>動画提出: <time datetime="{{ $entry->completed_at->format('Y-m-d\TH:i') }}"><span class="date">{{ $entry->completed_at->format('Y/m/d') }}</span> {{ $entry->completed_at->format('H:i') }}</time></span>
                    </li>
                    @endif
                </ul>
            </div>
            <div class="applicant-video">
                <h3>{{ $entry->user->name ?? 'Cafe' }}キャスト募集</h3>
                <div class="status">
                    @if($entry->video_path && $entry->status === 'completed' && empty($entry->decision_at))
                    <!-- 評価待ち画面 -->
                    <div class="video">
                        <span class="video-label waiting-list">評価待ち</span>

                        @if(isset($entryInterviews) && $entryInterviews->count() > 0)
                            <!-- 単一の動画プレーヤー -->
                            <div class="video-container" style="position: relative; max-width: 800px; margin: 0 auto;">
                                <video id="interview-video" class="custom-video-player" style="width: 100%;">
                                    <source id="video-source" src="" type="video/webm">
                                    お使いのブラウザは動画の再生をサポートしていません。
                                </video>
                                <div class="custom-controls">
                                    <button type="button" class="play-pause-btn">▶</button>
                                    <div class="volume-container">
                                        <span class="volume-icon">🔊</span>
                                        <input type="range" class="volume-slider" min="0" max="1" step="0.1" value="1">
                                    </div>
                                </div>
<<<<<<< HEAD
                                <div id="custom-subtitle" class="custom-subtitle"></div>
=======
                                <div id="custom-subtitle" class="custom-subtitle" style="position: absolute; bottom: 60px; left: 0; right: 0; background: rgba(0,0,0,0.8); color: white; padding: 12px 20px; text-align: center; font-size: 16px; line-height: 1.5; z-index: 10;"></div>
>>>>>>> 0c63ab33bf4160433b68b530c2ffab7cdc11506d
                            </div>

                            <!-- 質問データをJavaScriptに渡す -->
                            <script>
                                window.interviewQuestions = @json($interviewQuestionsData);
                            </script>
                        @else
                            <!-- 従来の動画表示（entryInterviewsがない場合のフォールバック） -->
                            <div class="video-container">
                                <video id="interview-video" class="custom-video-player" style="width: 100%; max-width: 800px;">
                                    @if(config('filesystems.default') === 's3')
                                        <source src="{{ route('record.video', ['filename' => basename($entry->video_path)]) }}" type="video/webm">
                                    @else
                                        <source src="{{ asset('storage/' . $entry->video_path) }}" type="video/webm">
                                        <source src="{{ route('record.video', ['filename' => basename($entry->video_path)]) }}" type="video/webm">
                                    @endif
                                    お使いのブラウザは動画の再生をサポートしていません。
                                </video>
                                <div class="custom-controls">
                                    <button type="button" class="play-pause-btn">▶</button>
                                    <div class="volume-container">
                                        <span class="volume-icon">🔊</span>
                                        <input type="range" class="volume-slider" min="0" max="1" step="0.1" value="1">
                                    </div>
                                </div>
                                <div id="custom-subtitle" class="custom-subtitle"></div>
                            </div>
                        @endif
                    </div>
                    <div class="judge-btns">
                        <button id="reject-btn" type="button" data-izimodal-open=".iziModal-rejected">不採用通知を送る</button>
                        <button id="pass-btn" class="passed" type="button" data-izimodal-open=".iziModal-passed">通過</button>
                    </div>

                    {{-- ローカル環境用：字幕埋め込みボタン --}}
                    @if(app()->isLocal())
                    <div style="margin-top: 10px; text-align: center; border: 1px dashed #ccc; padding: 10px;">
                        <p style="font-weight: bold; margin-bottom: 5px;">[DEV] 字幕焼き付けテスト</p>
                        <form action="{{ route('admin.entry.burn_subtitles', $entry->entry_id) }}" method="POST" onsubmit="return confirm('動画に字幕を埋め込みますか？（処理に時間がかかる場合があります）');">
                            @csrf
                            <button type="submit" style="background: #666; color: white; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer;">
                                字幕を動画に埋め込む（別ファイル生成）
                            </button>
                        </form>

                        {{-- 焼き付け済みファイルが存在する場合に表示 --}}
                        @php
                            $pathInfo = pathinfo($entry->video_path);
                            $burnedPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_burned.' . $pathInfo['extension'];
                            $isS3 = config('filesystems.default') === 's3';
                            $burnedExists = $isS3 ? \Illuminate\Support\Facades\Storage::disk('s3')->exists($burnedPath) : file_exists(storage_path('app/public/' . $burnedPath));
                        @endphp

                        @if($burnedExists)
                            <div style="margin-top: 15px;">
                                <p>▼ 字幕焼き付け済み動画（{{ basename($burnedPath) }}）</p>
                                <video controls style="width: 100%; max-width: 400px; border: 2px solid red;">
                                    @if($isS3)
                                        <source src="{{ route('record.video', ['filename' => basename($burnedPath)]) }}" type="video/webm">
                                    @else
                                        <source src="{{ route('record.video', ['filename' => basename($burnedPath)]) }}" type="video/webm">
                                    @endif
                                    お使いのブラウザは動画の再生をサポートしていません。
                                </video>
                            </div>
                        @endif
                    </div>
                    @endif

                    <small>応募者動画ファイルは回答後30日で削除されます</small>
                    @elseif($entry->status === 'rejected')
                    <!-- 不採用画面 -->
                    <div class="video">
                        <span class="video-label rejected">不採用</span>

                        @if(isset($entryInterviews) && $entryInterviews->count() > 0)
                            @foreach($entryInterviews as $index => $interview)
                                <div class="video-wrapper" style="margin-bottom: 2rem;">
                                    <h4 style="margin-bottom: 1rem; color: #333;">質問{{ $index + 1 }}: {{ $interview->question->q ?? '質問なし' }}</h4>
                                    <video class="custom-video-player" style="width: 100%; max-width: 800px;">
                                        @if(config('filesystems.default') === 's3')
                                            <source src="{{ route('record.video', ['filename' => basename($interview->file_path)]) }}" type="video/webm">
                                        @else
                                            <source src="{{ asset('storage/' . $interview->file_path) }}" type="video/webm">
                                            <source src="{{ route('record.video', ['filename' => basename($interview->file_path)]) }}" type="video/webm">
                                        @endif
                                    </video>
                                    <div class="custom-controls">
                                        <button type="button" class="play-pause-btn">▶</button>
                                        <div class="volume-container">
                                            <span class="volume-icon">🔊</span>
                                            <input type="range" class="volume-slider" min="0" max="1" step="0.1" value="1">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="video-wrapper">
                                <video class="custom-video-player" style="width: 100%; max-width: 800px;">
                                    @if(config('filesystems.default') === 's3')
                                        <source src="{{ route('record.video', ['filename' => basename($entry->video_path)]) }}" type="video/webm">
                                    @else
                                        <source src="{{ asset('storage/' . $entry->video_path) }}" type="video/webm">
                                        <source src="{{ route('record.video', ['filename' => basename($entry->video_path)]) }}" type="video/webm">
                                    @endif
                                </video>
                                <div class="custom-controls">
                                    <button type="button" class="play-pause-btn">▶</button>
                                    <div class="volume-container">
                                        <span class="volume-icon">🔊</span>
                                        <input type="range" class="volume-slider" min="0" max="1" step="0.1" value="1">
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <small>応募者動画ファイルは回答後30日で削除されます</small>
                    @elseif($entry->status === 'passed')
                    <!-- 通過画面 -->
                    <div class="video">
                        <span class="video-label passed">通過</span>

                        @if(isset($entryInterviews) && $entryInterviews->count() > 0)
                            @foreach($entryInterviews as $index => $interview)
                                <div class="video-wrapper" style="margin-bottom: 2rem;">
                                    <h4 style="margin-bottom: 1rem; color: #333;">質問{{ $index + 1 }}: {{ $interview->question->q ?? '質問なし' }}</h4>
                                    <video class="custom-video-player" style="width: 100%; max-width: 800px;">
                                        @if(config('filesystems.default') === 's3')
                                            <source src="{{ route('record.video', ['filename' => basename($interview->file_path)]) }}" type="video/webm">
                                        @else
                                            <source src="{{ asset('storage/' . $interview->file_path) }}" type="video/webm">
                                            <source src="{{ route('record.video', ['filename' => basename($interview->file_path)]) }}" type="video/webm">
                                        @endif
                                    </video>
                                    <div class="custom-controls">
                                        <button type="button" class="play-pause-btn">▶</button>
                                        <div class="volume-container">
                                            <span class="volume-icon">🔊</span>
                                            <input type="range" class="volume-slider" min="0" max="1" step="0.1" value="1">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="video-wrapper">
                                <video class="custom-video-player" style="width: 100%; max-width: 800px;">
                                    @if(config('filesystems.default') === 's3')
                                        <source src="{{ route('record.video', ['filename' => basename($entry->video_path)]) }}" type="video/webm">
                                    @else
                                        <source src="{{ asset('storage/' . $entry->video_path) }}" type="video/webm">
                                        <source src="{{ route('record.video', ['filename' => basename($entry->video_path)]) }}" type="video/webm">
                                    @endif
                                </video>
                                <div class="custom-controls">
                                    <button type="button" class="play-pause-btn">▶</button>
                                    <div class="volume-container">
                                        <span class="volume-icon">🔊</span>
                                        <input type="range" class="volume-slider" min="0" max="1" step="0.1" value="1">
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <small>応募者動画ファイルは回答後30日で削除されます</small>
                    @else
                    <!-- 未提出画面 -->
                    <span class="video-label not-submitted">未提出</span>
                    @if($entry->email || $entry->tel)
                    <button id="resend" type="button" class="create-url btn-md btn-green" data-izimodal-open=".iziModal-submit">面接URLを再送（残り{{ 3 - ($entry->retake_count ?? 0) }}回）</button>
                    @endif
                    <p class="copy-url-description">LINEやSNSで送る場合は、下記の面接URLをコピーしてご利用ください。</p>
                    <div class="copy-url">
                        <input id="url-display" class="display" type="url" value="{{ route('record.welcome', ['token' => $entry->interview_uuid]) }}" readonly>
                        <span id="copy-btn">
                            <img src="{{ asset('assets/admin/img/copy-icon.png') }}" alt="コピーアイコン">
                        </span>
                    </div>
                    @endif
                </div>
                <div class="back">
                    <a href="{{ route('admin.entry.index') }}">
                        <img src="{{ asset('assets/admin/img/left-chevron.png') }}" alt="戻るアイコン">
                        <span>一覧に戻る</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="privacy-policy">
            <a href="{{ route('company.policy') }}" target="_blank">個人情報の取り扱いについて</a>
        </div>
    </div>

    <!-- 不採用モーダル -->
    <div id="modal-reject" class="modal iziModal-rejected">
        <p class="rejected-confirm">本当にこの応募者を「不採用」にしますか？</p>
        <div class="modal-description">
            <p>「不採用」を選ぶと、登録されているメールまたは電話番号宛に不採用通知が自動送信されます。</p>
            <p>LINE等のSNS経由で応募された方には自動通知ができません。お手数ですが店舗様より直接「不採用」のご連絡をお願いいたします。</p>
        </div>
        <div class="modal-btns">
            <button id="cancel-btn" class="cancelled" data-izimodal-close="">キャンセル</button>
            <button id="rejected-btn" type="button" class="rejected-btn">不採用</button>
        </div>
    </div>

    <!-- 通過モーダル -->
    <div id="modal-accepted" class="modal iziModal-passed">
        <p class="passed-confirm">本当にこの応募者を「通過」にしますか？</p>
        <div class="modal-description">
            <p>「通過」を選ぶと、ステータスが「通過」に変更されます。</p>
            <p>通過のご連絡は、お手数ですが店舗様より直接ご連絡をお願いいたします。</p>
        </div>
        <div class="modal-btns">
            <button id="cancel-pass-btn" class="cancelled" data-izimodal-close="">キャンセル</button>
            <button id="pass-confirm-btn" type="button" class="passed">通過</button>
        </div>
    </div>

    <!-- 面接URL再送モーダル -->
    <div id="modal-submit" class="modal iziModal-submit">
        <p class="submit-confirm">面接URLを応募者へ送信しますか？</p>
        <div class="modal-btns submit-modal-btns">
            <button id="cancelled" class="cancelled" data-izimodal-close="">キャンセル</button>
            <button id="submit" type="button" class="inline-submit-btn">送信</button>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
// 質問切り替え機能
let currentQuestionIndex = 0;
let questions = [];

// カスタムビデオコントロールの初期化
document.addEventListener('DOMContentLoaded', function() {
    // 質問データが存在する場合
    if (window.interviewQuestions && window.interviewQuestions.length > 0) {
        questions = window.interviewQuestions;
        initQuestionPlayer();
    }

    const videoPlayers = document.querySelectorAll('.custom-video-player');

    videoPlayers.forEach(video => {
        const container = video.parentElement;
        const playBtn = container.querySelector('.play-pause-btn');
        const volumeSlider = container.querySelector('.volume-slider');
        const volumeIcon = container.querySelector('.volume-icon');

        // 動画のメタデータ読み込み後、向きをチェック
        video.addEventListener('loadedmetadata', () => {
            const width = video.videoWidth;
            const height = video.videoHeight;
            console.log('動画解像度:', width, 'x', height);

            // 横長の動画（width > height）の場合、CSS transformで90度回転
            if (width > height) {
                console.log('横長動画を検出、90度回転して縦向き表示にします');

                // ビデオコンテナの設定
                const videoContainer = container.parentElement;
                videoContainer.style.display = 'flex';
                videoContainer.style.justifyContent = 'center';
                videoContainer.style.alignItems = 'center';
                videoContainer.style.minHeight = '600px';

                // 動画を回転
                video.style.transform = 'rotate(90deg)';
                video.style.maxWidth = '600px';
                video.style.width = 'auto';
                video.style.height = 'auto';
            }
        });

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
            updateVolumeIcon(e.target.value);
        });

        function updateVolumeIcon(vol) {
            if (vol == 0) {
                volumeIcon.textContent = '🔇';
            } else if (vol < 0.5) {
                volumeIcon.textContent = '🔉';
            } else {
                volumeIcon.textContent = '🔊';
            }
        }
    });
});

// 質問切り替え機能の初期化（完全自動再生モード）
function initQuestionPlayer() {
    const video = document.getElementById('interview-video');
    const videoSource = document.getElementById('video-source');
    const subtitleDiv = document.getElementById('custom-subtitle');

    if (!video || !videoSource || questions.length === 0) return;

    // 質問を読み込む
    function loadQuestion(index) {
        if (index < 0 || index >= questions.length) return;

        currentQuestionIndex = index;
        const question = questions[index];

        // 動画ソースを変更
        videoSource.src = question.video_url;
        video.load();

        // 字幕を表示
        if (subtitleDiv) {
            subtitleDiv.textContent = `Q${index + 1}. ${question.question}`;
        }

        console.log(`質問${index + 1}を読み込みました:`, question.question);
    }

    // 動画が終了したら次の質問へ自動遷移
    video.addEventListener('ended', () => {
        if (currentQuestionIndex < questions.length - 1) {
            loadQuestion(currentQuestionIndex + 1);
            video.play();
        } else {
            console.log('全ての質問の再生が完了しました');
        }
    });

    // 最初の質問を読み込み（自動再生はしない）
    loadQuestion(0);
}

// VTTファイルを読み込んで字幕を表示（従来の動画用）
const video = document.getElementById('interview-video');
const subtitleDiv = document.getElementById('custom-subtitle');

if (video && subtitleDiv && !window.interviewQuestions) {
    // S3/Local問わず、record.videoルートを経由してVTTを取得する（MIMEタイプ設定とCORS回避のため）
    @if($entry->video_path)
        const vttPath = '{{ route("record.video", ["filename" => str_replace(".webm", ".vtt", basename($entry->video_path))]) }}';

        console.log('VTT Path:', vttPath);

        // VTTファイルを取得
        fetch(vttPath)
            .then(response => response.text())
            .then(vttText => {
                const cues = parseVTT(vttText);

                video.addEventListener('timeupdate', () => {
                    const currentTime = video.currentTime;
                    let currentCue = null;
                    for (const cue of cues) {
                        if (currentTime >= cue.start && currentTime <= cue.end) {
                            currentCue = cue;
                            break;
                        }
                    }

                    if (currentCue) {
                        subtitleDiv.textContent = currentCue.text;
                        subtitleDiv.style.display = 'block';
                    } else {
                        subtitleDiv.style.display = 'none';
                    }
                });
            })
            .catch(error => console.log('VTTファイルの読み込みに失敗:', error));
    @endif
}

function parseVTT(vttText) {
    const lines = vttText.split('\n');
    const cues = [];
    let i = 0;

    while (i < lines.length) {
        const line = lines[i].trim();

        if (line.includes('-->')) {
            const [start, end] = line.split('-->').map(t => t.trim().split(' ')[0]);
            i++;

            let text = '';
            while (i < lines.length && lines[i].trim() !== '') {
                text += lines[i].trim() + ' ';
                i++;
            }

            cues.push({
                start: parseVTTTime(start),
                end: parseVTTTime(end),
                text: text.trim()
            });
        }
        i++;
    }

    return cues;
}

function parseVTTTime(timeString) {
    const parts = timeString.split(':');
    const hours = parseInt(parts[0]);
    const minutes = parseInt(parts[1]);
    const seconds = parseFloat(parts[2]);
    return hours * 3600 + minutes * 60 + seconds;
}

// モーダルとAPI呼び出し
$(document).ready(function() {
    // iziModal初期化
    $(".iziModal-rejected").iziModal();
    $(".iziModal-passed").iziModal();
    $(".iziModal-submit").iziModal();

    // 不採用ボタン
    $('#rejected-btn').click(function() {
        const $btn = $(this);
        if ($btn.prop('disabled')) return;
        $btn.prop('disabled', true);

        $.ajax({
            url: '{{ route("admin.entry.reject", $entry->entry_id) }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('不採用処理が完了しました。');
                location.reload();
            },
            error: function(xhr) {
                alert('エラーが発生しました: ' + xhr.responseJSON.message);
                $btn.prop('disabled', false);
            }
        });
    });

    // 通過ボタン
    $('#pass-confirm-btn').click(function() {
        const $btn = $(this);
        if ($btn.prop('disabled')) return;
        $btn.prop('disabled', true);

        $.ajax({
            url: '{{ route("admin.entry.pass", $entry->entry_id) }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('通過処理が完了しました。');
                location.reload();
            },
            error: function(xhr) {
                alert('エラーが発生しました: ' + xhr.responseJSON.message);
                $btn.prop('disabled', false);
            }
        });
    });

    // 面接URL再送ボタン
    $('#submit').click(function() {
        const $btn = $(this);
        if ($btn.prop('disabled')) return;
        $btn.prop('disabled', true);

        $.ajax({
            url: '{{ route("admin.entry.resend", $entry->entry_id) }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert(response.message);
                location.reload();
            },
            error: function(xhr) {
                let message = 'エラーが発生しました';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message += ': ' + xhr.responseJSON.message;
                }
                alert(message);
                $btn.prop('disabled', false);
            }
        });
    });

    // URLコピー
    $('#copy-btn').click(function() {
        const urlInput = document.getElementById('url-display');
        urlInput.select();
        document.execCommand('copy');

        // メッセージを表示
        let $container = $(this).closest('.copy-url');
        let $msg = $container.find('.copy-message');

        if ($msg.length === 0) {
            $container.append('<span class="copy-message">コピーしました</span>');
            $msg = $container.find('.copy-message');
        }

        $msg.fadeIn(200).delay(1500).fadeOut(300);
    });
});
</script>
@endpush

