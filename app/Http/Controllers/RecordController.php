<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Question;
use App\Mail\AdminApplicantNotificationMail;
use App\Models\EntryInterview;
use App\Services\VideoProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RecordController extends Controller
{
        /**
     * サイトマップ ID: 2 - らくらくセルフ面接（求職者向け）
     * らくらくセルフ面接機能
     */
    public function record(Request $request)
    {
        $token = $request->get('token');
        $isValidToken = false;
        $entry = null;
        $errorMessage = null;

        // トークンが提供されている場合は検証
        if ($token) {
            // Entryテーブルからinterview_uuidでトークンを検索
            $entry = Entry::where('interview_uuid', $token)->first();

            if (!$entry) {
                $errorMessage = 'URLが見つかりません。<br>URLが正しいかご確認ください。';
            } elseif ($entry->status === 'completed') {
                $errorMessage = 'このURLは既に使用済みです。<br>新しいURLの発行をお店にご依頼ください。';
            } else {
                // 有効期限チェック（created_atから2週間）
                $expirationDays = config('app.interview_url_expiration_days', 14);
                $expiresAt = $entry->created_at->addDays($expirationDays);

                if (now()->gt($expiresAt)) {
                    $errorMessage = 'このURLの有効期限が切れています。<br>新しいURLの発行をお店にご依頼ください。';
                } else {
                    $isValidToken = true;
                }
            }
        } else {
            $errorMessage = '面接URLが指定されていません。';
        }

        // category_id=2の質問を取得（面接用質問）
        $questions = [];
        if ($isValidToken) {
            $questions = Question::where('category_id', 2)
                                ->orderBy('order')
                                ->get();
        }

        return view('record.index', [
            'token' => $token,
            'isValidToken' => $isValidToken,
            'entry' => $entry,
            'errorMessage' => $errorMessage,
            'questions' => $questions
        ]);
    }

    /**
     * 面接動画をアップロード
     */
    public function upload(Request $request)
    {
        $request->validate([
            'video' => 'required|file|mimetypes:video/webm,video/mp4,video/quicktime|max:102400', // 100MB
            'token' => 'required|string'
        ]);

        $token = $request->input('token');
        $questionNumber = $request->input('question_number');
        $entry = Entry::where('interview_uuid', $token)->first();

        if (!$entry) {
            return response()->json([
                'success' => false,
                'message' => '無効なトークンです。'
            ], 400);
        }

        if ($entry->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'このインタビューは既に完了しています。'
            ], 400);
        }

        try {
            // 動画ファイルを保存
            $videoFile = $request->file('video');

            // ファイル拡張子を取得
            $extension = $videoFile->getClientOriginalExtension();
            if (empty($extension)) {
                // MIMEタイプから拡張子を決定
                $mimeType = $videoFile->getMimeType();
                switch ($mimeType) {
                    case 'video/webm':
                        $extension = 'webm';
                        break;
                    case 'video/mp4':
                        $extension = 'mp4';
                        break;
                    case 'video/quicktime':
                        $extension = 'mov';
                        break;
                    default:
                        $extension = 'webm';
                        break;
                }
            }

            // 質問番号がある場合は質問ごとのファイル名、ない場合は従来のファイル名
            if ($questionNumber) {
                $fileName = 'interview_q_' . Str::random(40) . '.' . $extension;
            } else {
                $fileName = 'interview_' . Str::random(40) . '.' . $extension;
            }

            // ファイルを保存
            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
            $path = $videoFile->storeAs('interviews', $fileName, $disk);

            Log::info("ファイル保存完了: fileName={$fileName}, path={$path}, disk={$disk}");
            if ($disk === 'public') {
                Log::info("ファイル保存場所確認: " . storage_path('app/public/' . $path));
                Log::info("公開URL確認: " . asset('storage/' . $path));
            }

            // 質問番号がある場合はentry_interviewsテーブルに保存（字幕なし）
            if ($questionNumber) {
                Log::info("質問検索開始: questionNumber={$questionNumber}");

                $allQuestions = Question::where('category_id', 2)->orderBy('order')->get();
                Log::info("全質問数: " . $allQuestions->count());

                // 全質問の詳細をログ出力
                foreach ($allQuestions as $idx => $q) {
                    Log::info("質問データ[{$idx}]: question_id={$q->question_id}, order={$q->order}, text={$q->q}");
                }

                // 質問番号（1,2,3）を配列インデックス（0,1,2）に変換して対応する質問を取得
                $questionIndex = $questionNumber - 1;
                $question = $allQuestions->get($questionIndex);

                Log::info("取得した質問: questionIndex={$questionIndex}, question_id=" . ($question ? $question->question_id : 'null') . ", text=" . ($question ? $question->q : 'null'));

                if ($question) {
                    // 字幕なしで元の動画ファイルを保存
                    $originalVideoPath = $path;

                    Log::info("字幕なし動画保存: original={$originalVideoPath}, question_id={$question->question_id}");

                    $entryInterview = EntryInterview::create([
                        'entry_id' => $entry->entry_id,
                        'question_id' => $question->question_id,
                        'file_path' => $originalVideoPath
                    ]);

                    Log::info("EntryInterview保存完了: ID={$entryInterview->interview_id}, entry_id={$entry->entry_id}, question_id={$question->question_id}, path={$originalVideoPath}");
                } else {
                    Log::error("質問が見つかりません: questionNumber={$questionNumber}, category_id=2");
                }
            }            // 最後の質問の場合のみEntryレコードを更新
            if (!$questionNumber || $questionNumber == $request->input('total_questions')) {
                $entry->update([
                    'status' => 'completed',
                    'video_path' => $path,
                    'completed_at' => now()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => $questionNumber ? "質問{$questionNumber}の動画がアップロードされました。" : '面接動画がアップロードされました。',
                'file_path' => $path
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'アップロードに失敗しました: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 全質問動画のプレビュー生成
     */
    public function preview(Request $request)
    {
        $token = $request->input('token');
        $entry = Entry::where('interview_uuid', $token)->first();

        if (!$entry) {
            return response()->json([
                'success' => false,
                'message' => '無効なトークンです。'
            ], 400);
        }

        if ($entry->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'このインタビューは既に完了しています。'
            ], 400);
        }

        // entry_interviewsから動画ファイルパスを取得
        $interviews = EntryInterview::where('entry_id', $entry->entry_id)
                                  ->with('question')
                                  ->orderBy('question_id')
                                  ->get();

        Log::info("プレビュー: entry_id={$entry->entry_id}, 動画件数=" . $interviews->count());

        $videoData = [];
        foreach ($interviews as $interview) {
            $filePath = $interview->file_path;
            $fullPath = storage_path('app/public/' . $filePath);
            $publicPath = public_path('storage/' . $filePath);
            $fileExists = file_exists($fullPath);
            $publicExists = file_exists($publicPath);

            Log::info("動画ファイル確認: file_path={$filePath}");
            Log::info("フルパス: {$fullPath}, 存在={$fileExists}");
            Log::info("公開パス: {$publicPath}, 存在={$publicExists}");

            // ファイル情報を取得
            $fileSize = $fileExists ? filesize($fullPath) : 0;
            $mimeType = $fileExists ? mime_content_type($fullPath) : 'unknown';

            // ファイル名を抽出してカスタムルートを使用
            $fileName = basename($filePath);
            $customVideoUrl = route('record.video', ['filename' => $fileName]);

            $videoData[] = [
                'question_text' => $interview->question->q ?? '質問テキストなし',
                'video_path' => $customVideoUrl,
                'asset_path' => asset('storage/' . $filePath),
                'file_path' => $filePath,
                'file_exists' => $fileExists,
                'public_exists' => $publicExists,
                'full_path' => $fullPath,
                'public_path' => $publicPath,
                'file_size' => $fileSize,
                'mime_type' => $mimeType,
                'filename' => $fileName
            ];
        }

        return response()->json([
            'success' => true,
            'videos' => $videoData
        ]);
    }

    /**
     * 最終面接動画を結合して送信
     */
    public function submit(Request $request)
    {
        // メモリ制限を一時的に増やす
        ini_set('memory_limit', '512M');

        $token = $request->input('token');
        $entry = Entry::where('interview_uuid', $token)->first();

        if (!$entry) {
            return response()->json([
                'success' => false,
                'message' => '無効なトークンです。'
            ], 400);
        }

        if ($entry->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'このインタビューは既に完了しています。'
            ], 400);
        }

        // 動画ファイルを確認
        if (!$request->hasFile('video')) {
            return response()->json([
                'success' => false,
                'message' => '動画ファイルがアップロードされていません。'
            ], 400);
        }

        try {
            Log::info("送信処理開始: entry_id={$entry->entry_id}");

            $videoFile = $request->file('video');

            Log::info("アップロードファイル情報: ", [
                'original_name' => $videoFile->getClientOriginalName(),
                'size' => $videoFile->getSize(),
                'mime_type' => $videoFile->getMimeType(),
                'extension' => $videoFile->getClientOriginalExtension()
            ]);

            // ファイルの検証
            if (!$videoFile->isValid()) {
                throw new \Exception('アップロードされた動画ファイルが無効です。');
            }

            // ファイル名を生成（実際の拡張子を使用）
            $extension = $videoFile->getClientOriginalExtension();
            // 拡張子が取得できない場合はmimeTypeから推測
            if (!$extension) {
                $mimeType = $videoFile->getMimeType();
                if (strpos($mimeType, 'mp4') !== false) {
                    $extension = 'mp4';
                } elseif (strpos($mimeType, 'webm') !== false) {
                    $extension = 'webm';
                } else {
                    $extension = 'webm'; // デフォルト
                }
            }

            $fileName = 'interview_' . Str::random(40) . '.' . $extension;
            $filePath = 'interviews/' . $fileName;

            Log::info("ファイル名生成: " . $fileName);

            // S3に保存
            try {
                $path = $videoFile->storeAs('interviews', $fileName, 's3');
                Log::info("S3保存完了: " . $path);

                // メモリ解放
                unset($videoFile);
                gc_collect_cycles();
            } catch (\Exception $e) {
                Log::error("S3保存エラー: " . $e->getMessage());
                throw new \Exception('S3への保存に失敗しました: ' . $e->getMessage());
            }

            // 字幕ファイルを生成
            $timestamps = json_decode($request->input('timestamps', '[]'), true);
            if (!empty($timestamps)) {
                $vttFileName = pathinfo($fileName, PATHINFO_FILENAME) . '.vtt'; // 拡張子に依存しない方法

                $vttContent = "WEBVTT\n\n";

                foreach ($timestamps as $index => $ts) {
                    $startTime = $this->formatVttTime($ts['startTime']);
                    // 各質問は8秒間表示
                    $endTime = $this->formatVttTime($ts['startTime'] + 8000);

                    $vttContent .= ($index + 1) . "\n";
                    $vttContent .= "{$startTime} --> {$endTime} line:90% position:50% align:center\n";
                    $vttContent .= "Q" . ($index + 1) . ": " . $ts['question'] . "\n\n";
                }

                // S3に字幕ファイルを保存
                Storage::disk('s3')->put('interviews/' . $vttFileName, $vttContent);

                Log::info("字幕ファイルS3保存: ", [
                    'vtt_path' => 'interviews/' . $vttFileName,
                    'timestamps_count' => count($timestamps)
                ]);
            }

            // Entryレコードを更新
            $entry->update([
                'status' => 'completed',
                'video_path' => $filePath,
                'completed_at' => now()
            ]);

            // 面接完了メールを送信
            // 1. 応募者へ（必要であれば実装）
            // if ($entry->email) {
            //     Mail::to($entry->email)->send(new InterviewCompletedMail($entry));
            // }

            // 2. 管理者へ通知（動画提出のお知らせ）
            if ($entry->user && $entry->user->email) {
                try {
                    Mail::to($entry->user->email)->send(new AdminApplicantNotificationMail($entry, 'review_request'));
                    Log::info("管理者へ動画提出通知メール送信: admin_email={$entry->user->email}");
                } catch (\Exception $e) {
                    Log::error("管理者へ動画提出通知メール送信失敗: " . $e->getMessage());
                }
            }

            Log::info("送信処理完了: entry_id={$entry->entry_id}");

            return response()->json([
                'success' => true,
                'message' => '面接動画を送信しました。'
            ]);

        } catch (\Exception $e) {
            Log::error("送信処理エラー: " . $e->getMessage());
            Log::error("エラー発生場所: " . $e->getFile() . ":" . $e->getLine());
            Log::error("スタックトレース: " . $e->getTraceAsString());

            // エラーの種類を判定
            $errorType = get_class($e);
            $errorDetails = [
                'error_type' => $errorType,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode()
            ];

            return response()->json([
                'success' => false,
                'message' => '送信に失敗しました: ' . $e->getMessage(),
                'error_details' => $errorDetails
            ], 500);
        }
    }

    /**
     * 録り直し機能
     */
    public function retake(Request $request)
    {
        $token = $request->input('token');
        $entry = Entry::where('interview_uuid', $token)->first();

        if (!$entry) {
            return response()->json([
                'success' => false,
                'message' => '無効なトークンです。'
            ], 400);
        }

        if ($entry->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'このインタビューは既に完了しています。'
            ], 400);
        }

        // 録り直し回数をチェック（1回まで）
        $retakeCount = $entry->retake_count ?? 0;
        if ($retakeCount >= 1) {
            return response()->json([
                'success' => false,
                'message' => '録り直し回数の上限に達しています。'
            ], 400);
        }

        try {
            // 既存の動画データを削除
            EntryInterview::where('entry_id', $entry->entry_id)->delete();

            // 字幕入り動画ファイルを削除
            $videoPath = $request->input('videoPath');
            if ($videoPath) {
                $fullPath = storage_path('app/public/' . $videoPath);
                $vttPath = str_replace('.webm', '.vtt', $fullPath);

                if (file_exists($fullPath)) {
                    unlink($fullPath);
                    Log::info("動画ファイル削除: {$fullPath}");
                }

                if (file_exists($vttPath)) {
                    unlink($vttPath);
                    Log::info("字幕ファイル削除: {$vttPath}");
                }
            }

            // 録り直し回数を増加
            $entry->retake_count = $retakeCount + 1;
            $entry->status = 'recording';
            $entry->save();

            return response()->json([
                'success' => true,
                'message' => '録り直しを開始します。',
                'remaining_retakes' => 1 - ($retakeCount + 1)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '録り直しの開始に失敗しました: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 途中やり直し機能
     */
    public function interrupt(Request $request)
    {
        $token = $request->input('token');
        $entry = Entry::where('interview_uuid', $token)->first();

        if (!$entry) {
            return response()->json([
                'success' => false,
                'message' => '無効なトークンです。'
            ], 400);
        }

        if ($entry->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'このインタビューは既に完了しています。'
            ], 400);
        }

        // 途中やり直し回数をチェック（1回まで）
        $interruptRetakeCount = $entry->interrupt_retake_count ?? 0;
        if ($interruptRetakeCount >= 1) {
            return response()->json([
                'success' => false,
                'message' => 'やり直し回数の上限に達しています。'
            ], 400);
        }

        try {
            // 既存の動画データを削除
            EntryInterview::where('entry_id', $entry->entry_id)->delete();

            // 途中やり直し回数を増加
            $entry->interrupt_retake_count = $interruptRetakeCount + 1;
            $entry->status = 'recording';
            $entry->save();

            return response()->json([
                'success' => true,
                'message' => '最初からやり直します。',
                'remaining_retakes' => 1 - ($interruptRetakeCount + 1)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'やり直しの開始に失敗しました: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 動画ファイルを配信（Range リクエスト対応）
     */
    public function serveVideo($filename)
    {
        // 拡張子を取得
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $isVtt = strtolower($extension) === 'vtt';

        // S3の場合
        if (config('filesystems.default') === 's3') {
            $path = 'interviews/' . $filename;
            if (!Storage::disk('s3')->exists($path)) {
                abort(404, 'Video file not found');
            }

            // VTTファイルの場合は内容を直接返す（CORS回避のため）
            if ($isVtt) {
                $content = Storage::disk('s3')->get($path);
                return response($content, 200, [
                    'Content-Type' => 'text/vtt',
                    'Cache-Control' => 'no-cache',
                    'Access-Control-Allow-Origin' => '*',
                ]);
            }

            // 5分間有効な署名付きURLを発行してリダイレクト
            return redirect(Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(5)));
        }

        $path = storage_path('app/public/interviews/' . $filename);

        if (!file_exists($path)) {
            abort(404, 'Video file not found');
        }

        $mimeType = mime_content_type($path);
        // VTTファイルの場合はMIMEタイプを強制
        if ($isVtt) {
            $mimeType = 'text/vtt';
        }

        $fileSize = filesize($path);

        $headers = [
            'Content-Type' => $mimeType,
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'public, max-age=86400',
        ];

        // Range リクエストの処理
        $range = request()->header('Range');

        if ($range) {
            // Range: bytes=0-1023 の形式をパース
            if (preg_match('/bytes=(\d*)-(\d*)/', $range, $matches)) {
                $start = $matches[1] !== '' ? intval($matches[1]) : 0;
                $end = $matches[2] !== '' ? intval($matches[2]) : $fileSize - 1;

                if ($start > $end || $start >= $fileSize) {
                    return response('', 416, ['Content-Range' => "bytes */{$fileSize}"]);
                }

                $end = min($end, $fileSize - 1);
                $length = $end - $start + 1;

                $headers['Content-Length'] = $length;
                $headers['Content-Range'] = "bytes {$start}-{$end}/{$fileSize}";

                return response()->stream(function () use ($path, $start, $length) {
                    $stream = fopen($path, 'r');
                    fseek($stream, $start);
                    $remaining = $length;

                    while ($remaining > 0 && !feof($stream)) {
                        $chunk = min(8192, $remaining);
                        echo fread($stream, $chunk);
                        $remaining -= $chunk;
                        flush();
                    }

                    fclose($stream);
                }, 206, $headers);
            }
        }

        // 通常のレスポンス
        $headers['Content-Length'] = $fileSize;

        return response()->stream(function () use ($path) {
            $stream = fopen($path, 'r');
            fpassthru($stream);
            fclose($stream);
        }, 200, $headers);
    }

    /**
     * 面接完了ページ
     */
    public function complete(Request $request)
    {
        $token = $request->get('token');
        $entry = null;

        if ($token) {
            $entry = Entry::where('interview_uuid', $token)->first();
        }

        return view('record.complete', compact('token', 'entry'));
    }

    /**
     * ウェルカムページ（新しいUI）
     */
    public function welcome(Request $request)
    {
        $token = $request->get('token');

        if (!$token) {
            return view('record.error')->with('errorMessage', '面接URLが指定されていません。');
        }

        $entry = Entry::where('interview_uuid', $token)->first();

        if (!$entry) {
            return view('record.error')->with('errorMessage', 'URLが見つかりません。<br>URLが正しいかご確認ください。');
        }

        // 評価済み（採用・不採用が決定）の場合はエラー
        if ($entry->status === 'rejected' || $entry->status === 'passed' || $entry->decision_at !== null) {
            return view('record.error')->with('errorMessage', 'このURLは既に使用済みです。<br>新しいURLの発行をお店にご依頼ください。');
        }

        if ($entry->status === 'completed') {
            return view('record.error')->with('errorMessage', '面接動画を受け付けました。<br>評価結果をお待ちください。');
        }

        // 確認画面待機中の場合は確認画面へリダイレクト
        // if ($entry->status === 'confirming') {
        //     return redirect()->route('record.confirm', ['token' => $token]);
        // }

        $expirationDays = config('app.interview_url_expiration_days', 14);
        $expiresAt = $entry->created_at->addDays($expirationDays);

        if (now()->gt($expiresAt)) {
            return view('record.error')->with('errorMessage', 'このURLの有効期限が切れています。新しいURLの発行をお店にご依頼ください。');
        }

        return view('record.welcome', compact('token', 'entry'));
    }

    /**
     * やり方説明ページ
     */
    public function howto(Request $request)
    {
        $token = $request->get('token');

        $entry = Entry::where('interview_uuid', $token)->first();

        // 評価済みの場合はエラー
        if ($entry && ($entry->status === 'rejected' || $entry->status === 'passed' || $entry->decision_at !== null)) {
            return view('record.error')->with('errorMessage', 'このURLは既に使用済みです。');
        }

        // if ($entry && $entry->status === 'confirming') {
        //     return redirect()->route('record.confirm', ['token' => $token]);
        // }

        return view('record.howto', compact('token'));
    }

    /**
     * 面接プレビューページ
     */
    public function interviewPreview(Request $request)
    {
        $token = $request->get('token');

        $entry = Entry::where('interview_uuid', $token)->first();

        // 既に送信済みの場合はエラーページへ
        if ($entry && $entry->status === 'completed') {
            return redirect()->route('record.error', ['token' => $token, 'message' => 'このインタビューは既に送信済みです。']);
        }

        // 確認画面待機中の場合は確認画面へリダイレクト
        // if ($entry && $entry->status === 'confirming') {
        //     return redirect()->route('record.confirm', ['token' => $token]);
        // }

        $questions = Question::where('category_id', 2)->orderBy('order')->get();

        return view('record.interview-preview', compact('token', 'entry', 'questions'));
    }

    /**
     * 面接開始ページ
     */
    public function interview(Request $request)
    {
        $token = $request->get('token');

        $entry = Entry::where('interview_uuid', $token)->first();

        // 評価済みの場合はエラー
        if ($entry && ($entry->status === 'rejected' || $entry->status === 'passed' || $entry->decision_at !== null)) {
            return view('record.error')->with('errorMessage', 'このURLは既に使用済みです。');
        }

        // 既に送信済みの場合はエラーページへ
        if ($entry && $entry->status === 'completed') {
            return redirect()->route('record.error', ['token' => $token, 'message' => 'このインタビューは既に送信済みです。']);
        }

        // 確認画面待機中かつやり直し回数が0の場合は確認画面へリダイレクト
        if ($entry && $entry->status === 'confirming') {
            $interruptRetakeCount = $entry->interrupt_retake_count ?? 0;
            if ($interruptRetakeCount >= 1) {
                // やり直し回数が残っていない場合は確認画面に戻す
                return redirect()->route('record.confirm', ['token' => $token])
                    ->with('warning', 'やり直し回数が残っていないため、面接画面にアクセスできません。');
            }
        }

        //$questions = Question::where('category_id', 2)->orderBy('order')->get();
        $questions = Question::where('category_id', 2)->orderBy('order')->take(15)->get();

        return view('record.interview', compact('token', 'entry', 'questions'));
    }

    /**
     * 確認画面
     */
    public function confirm(Request $request)
    {
        $token = $request->get('token');
        $entry = Entry::where('interview_uuid', $token)->first();

        // 評価済みの場合はエラー
        if ($entry && ($entry->status === 'rejected' || $entry->status === 'passed' || $entry->decision_at !== null)) {
            return view('record.error')->with('errorMessage', 'このURLは既に使用済みです。');
        }

        // 既に送信済みの場合はエラーページへ
        if ($entry && $entry->status === 'completed') {
            return redirect()->route('record.error', ['token' => $token, 'message' => 'このインタビューは既に送信済みです。']);
        }

        // ステータスを確認画面待機中に更新
        if ($entry && $entry->status !== 'completed' && $entry->status !== 'confirming') {
            $entry->status = 'confirming';
            $entry->save();
        }

        // メモリ制限のため、質問数を5問に制限
        $questions = Question::where('category_id', 2)->orderBy('order')->take(5)->get();

        return view('record.confirm', compact('token', 'entry', 'questions'));
    }

    /**
     * エラーページ
     */
    public function error(Request $request)
    {
        $token = $request->get('token');
        $errorMessage = $request->get('message', 'エラーが発生しました。');

        return view('record.error', compact('token', 'errorMessage'));
    }

    /**
     * 字幕処理（動画に質問字幕を追加）
     */
    public function processSubtitles(Request $request)
    {
        $token = $request->input('token');
        $entry = Entry::where('interview_uuid', $token)->first();

        if (!$entry) {
            return response()->json([
                'success' => false,
                'message' => '有効なトークンが見つかりません。'
            ], 404);
        }

        if ($entry->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'このインタビューは既に完了しています。'
            ], 400);
        }

        if (!$request->hasFile('video')) {
            return response()->json([
                'success' => false,
                'message' => '動画ファイルがアップロードされていません。'
            ], 400);
        }

        try {
            $videoFile = $request->file('video');
            $timestamps = json_decode($request->input('timestamps', '[]'), true);

            $randomStr = Str::random(40);

            // 元の動画を保存
            $fileName = 'interview_' . $randomStr . '.webm';

            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
            $path = $videoFile->storeAs('interviews', $fileName, $disk);

            // 字幕ファイルを生成（WebVTT形式）
            $vttFileName = 'interview_' . $randomStr . '.vtt';

            $vttContent = "WEBVTT\n\n";

            foreach ($timestamps as $index => $ts) {
                $startTime = $this->formatVttTime($ts['startTime']);
                // 各質問は8秒間表示
                $endTime = $this->formatVttTime($ts['startTime'] + 8000);

                $vttContent .= ($index + 1) . "\n";
                $vttContent .= "{$startTime} --> {$endTime}\n";
                $vttContent .= "Q" . ($index + 1) . ": " . $ts['question'] . "\n\n";
            }

            if ($disk === 's3') {
                Storage::disk('s3')->put('interviews/' . $vttFileName, $vttContent);
            } else {
                $storageDir = storage_path('app/public/interviews');
                if (!file_exists($storageDir)) {
                    mkdir($storageDir, 0755, true);
                }
                file_put_contents($storageDir . '/' . $vttFileName, $vttContent);
            }

            Log::info("字幕処理完了: ", [
                'video_path' => $fileName,
                'vtt_path' => $vttFileName,
                'timestamps_count' => count($timestamps)
            ]);

            return response()->json([
                'success' => true,
                'message' => '字幕処理が完了しました。',
                'videoPath' => 'interviews/' . $fileName,
                'vttPath' => 'interviews/' . $vttFileName
            ]);

        } catch (\Exception $e) {
            Log::error("字幕処理エラー: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '字幕処理に失敗しました: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * VTT時間フォーマット（ミリ秒 → HH:MM:SS.mmm）
     */
    private function formatVttTime($milliseconds)
    {
        $seconds = floor($milliseconds / 1000);
        $ms = $milliseconds % 1000;
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        return sprintf('%02d:%02d:%02d.%03d', $hours, $minutes, $secs, $ms);
    }
}
