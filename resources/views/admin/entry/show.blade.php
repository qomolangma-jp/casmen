@extends('layouts.admin')

@section('title', 'CASMEN｜応募者詳細')

@push('styles')
<style>
    /* カスタム字幕スタイル */
    .custom-subtitle {
        position: absolute;
        top: 90%;
        left: 50%;
        transform: translate(-50%, -100%);
        background-color: rgba(0, 0, 0, 0.85);
        color: white;
        padding: 10px 20px;
        font-size: 15px;
        text-align: center;
        width: 90%;
        display: none;
        z-index: 10;
        border-radius: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
                        <div class="video-container">
                            <video id="interview-video" controls style="width: 100%; max-width: 800px;">
                                @if(config('filesystems.default') === 's3')
                                    <source src="{{ route('record.video', ['filename' => basename($entry->video_path)]) }}" type="video/webm">
                                @else
                                    <source src="{{ asset('storage/' . $entry->video_path) }}" type="video/webm">
                                    <source src="{{ route('record.video', ['filename' => basename($entry->video_path)]) }}" type="video/webm">
                                @endif
                                お使いのブラウザは動画の再生をサポートしていません。
                            </video>
                            <div id="custom-subtitle" class="custom-subtitle"></div>
                        </div>
                    </div>
                    <div class="judge-btns">
                        <button id="reject-btn" type="button" data-izimodal-open=".iziModal-rejected">不採用通知を送る</button>
                        <button id="pass-btn" class="passed" type="button" data-izimodal-open=".iziModal-passed">通過</button>
                    </div>

                    {{-- ローカル環境またはデバッグ用：字幕埋め込みボタン --}}
                    @if(app()->isLocal() || config('app.debug'))
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
                        <video controls style="width: 100%; max-width: 800px;">
                            @if(config('filesystems.default') === 's3')
                                <source src="{{ route('record.video', ['filename' => basename($entry->video_path)]) }}" type="video/webm">
                            @else
                                <source src="{{ asset('storage/' . $entry->video_path) }}" type="video/webm">
                                <source src="{{ route('record.video', ['filename' => basename($entry->video_path)]) }}" type="video/webm">
                            @endif
                        </video>
                    </div>
                    <small>応募者動画ファイルは回答後30日で削除されます</small>
                    @elseif($entry->status === 'passed')
                    <!-- 通過画面 -->
                    <div class="video">
                        <span class="video-label passed">通過</span>
                        <video controls style="width: 100%; max-width: 800px;">
                            @if(config('filesystems.default') === 's3')
                                <source src="{{ route('record.video', ['filename' => basename($entry->video_path)]) }}" type="video/webm">
                            @else
                                <source src="{{ asset('storage/' . $entry->video_path) }}" type="video/webm">
                                <source src="{{ route('record.video', ['filename' => basename($entry->video_path)]) }}" type="video/webm">
                            @endif
                        </video>
                    </div>
                    <small>応募者動画ファイルは回答後30日で削除されます</small>
                    @else
                    <!-- 未提出画面 -->
                    <span class="video-label not-submitted">未提出</span>
                    @if($entry->email || $entry->tel)
                    <button id="resend" type="button" class="create-url" data-izimodal-open=".iziModal-submit">面接URLを再送（残り{{ 3 - ($entry->retake_count ?? 0) }}回）</button>
                    <p class="copy-url-description">LINEやSNSで送る場合は、下記の面接URLをコピーしてご利用ください。</p>
                    <div class="copy-url">
                        <input id="url-display" class="display" type="url" value="{{ route('record.welcome', ['token' => $entry->interview_uuid]) }}" readonly>
                        <span id="copy-btn">
                            <img src="{{ asset('assets/admin/img/copy-icon.png') }}" alt="コピーアイコン">
                        </span>
                    </div>
                    @endif
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
            <a href="#">個人情報の取り扱いについて</a>
        </div>
    </div>

    <!-- 不採用モーダル -->
    <div id="modal-reject" class="modal iziModal-rejected">
        <p class="rejected-confirm">本当にこの応募者を「不採用」にしますか？</p>
        <div class="modal-description">
            <p>「不採用」を選ぶと、登録されているメールまたは電話番号宛に不採用通知が自動送信されます。</p>
            <p>LINESNS経由で応募された方には自動通知ができません。お手数ですが店舗様より直接「不採用」のご連絡をお願いいたします。</p>
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
// VTTファイルを読み込んで字幕を表示
const video = document.getElementById('interview-video');
const subtitleDiv = document.getElementById('custom-subtitle');

if (video && subtitleDiv) {
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

