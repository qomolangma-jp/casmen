<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Question;
use App\Models\EntryInterview;
use App\Services\VideoProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
                $errorMessage = 'URLが見つかりません。URLが正しいかご確認ください。';
            } elseif ($entry->status === 'completed') {
                $errorMessage = 'このURLは既に使用済みです。新しいURLの発行をお店にご依頼ください。';
            } else {
                // 有効期限チェック（created_atから2週間）
                $expirationDays = config('app.interview_url_expiration_days', 14);
                $expiresAt = $entry->created_at->addDays($expirationDays);

                if (now()->gt($expiresAt)) {
                    $errorMessage = 'このURLの有効期限が切れています。新しいURLの発行をお店にご依頼ください。';
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
                $fileName = 'interview_' . $entry->entry_id . '_question_' . $questionNumber . '_' . time() . '.' . $extension;
            } else {
                $fileName = 'interview_' . $entry->entry_id . '_' . time() . '.' . $extension;
            }

            // ファイルを保存
            $path = $videoFile->storeAs('interviews', $fileName, 'public');

            Log::info("ファイル保存完了: fileName={$fileName}, path={$path}");
            Log::info("ファイル保存場所確認: " . storage_path('app/public/' . $path));
            Log::info("公開URL確認: " . asset('storage/' . $path));

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
        $token = $request->input('token');
        $entry = Entry::where('interview_uuid', $token)->first();

        if (!$entry) {
            return response()->json([
                'success' => false,
                'message' => '無効なトークンです。'
            ], 400);
        }

        try {
            Log::info("送信処理開始: entry_id={$entry->entry_id}");

            // entry_interviewsから動画を取得（重複を防ぐために各質問の最新のもののみ）
            $interviews = EntryInterview::where('entry_id', $entry->entry_id)
                                      ->with('question')
                                      ->orderBy('question_id')
                                      ->orderBy('created_at', 'desc')
                                      ->get()
                                      ->groupBy('question_id')
                                      ->map(function ($group) {
                                          return $group->first(); // 各質問の最新のもののみ
                                      })
                                      ->sortBy('question_id')
                                      ->values();

            Log::info("EntryInterview検索結果: entry_id={$entry->entry_id}, count=" . $interviews->count());

            // 各動画の詳細をログ出力
            foreach ($interviews as $interview) {
                Log::info("質問{$interview->question_id}: {$interview->file_path}");
            }

            // 期待される質問数を確認（category_id=2の質問のみ）
            $totalQuestions = Question::where('category_id', 2)->count();
            Log::info("期待される質問数: {$totalQuestions}, 実際の面接動画数: " . $interviews->count());

            if ($interviews->isEmpty()) {
                // デバッグ情報を追加
                $allInterviews = EntryInterview::all();
                Log::info("全EntryInterview件数: " . $allInterviews->count());
                foreach ($allInterviews as $item) {
                    Log::info("EntryInterview: ID={$item->interview_id}, entry_id={$item->entry_id}, question_id={$item->question_id}, file_path={$item->file_path}");
                }

                return response()->json([
                    'success' => false,
                    'message' => '録画された動画が見つかりません。entry_id: ' . $entry->entry_id
                ], 400);
            }

            // Step 1: VideoProcessingService初期化
            Log::info("VideoProcessingService初期化開始");
            $videoProcessingService = new VideoProcessingService();
            Log::info("VideoProcessingService初期化完了");

            $processedVideoPaths = [];

            foreach ($interviews as $index => $interview) {
                Log::info("動画に字幕追加開始: question_id={$interview->question_id}, file_path={$interview->file_path}");

                // 質問番号は順序通り（1,2,3）
                $questionNumber = $index + 1;

                // 字幕付き動画を生成
                $processedVideoPath = $videoProcessingService->addQuestionOverlay(
                    $interview->file_path,
                    $interview->question->q,
                    $questionNumber
                );

                if ($processedVideoPath) {
                    $processedVideoPaths[] = $processedVideoPath;
                    Log::info("字幕追加成功: question_{$questionNumber} -> {$processedVideoPath}");
                } else {
                    // 字幕追加に失敗した場合は元の動画を使用
                    $processedVideoPaths[] = $interview->file_path;
                    Log::warning("字幕追加失敗、元動画使用: question_{$questionNumber} -> {$interview->file_path}");
                }
            }

            Log::info("字幕付き動画結合開始: " . implode(', ', $processedVideoPaths));

            // Step 2: 字幕付き動画を結合
            $combinedFileName = 'interviews/combined_interview_' . $entry->entry_id . '_' . time() . '.mp4';
            $combineSuccess = $videoProcessingService->concatVideos($processedVideoPaths, $combinedFileName);

            if ($combineSuccess) {
                $finalVideoPath = $combinedFileName;
                Log::info("動画結合成功: {$finalVideoPath}");
            } else {
                // 結合に失敗した場合は最初の字幕付き動画を使用
                $finalVideoPath = $processedVideoPaths[0] ?? $interviews->first()->file_path;
                Log::warning("動画結合失敗、最初の動画を使用: {$finalVideoPath}");
            }

            // Entryレコードを更新
            $entry->update([
                'status' => 'submitted',
                'video_path' => $finalVideoPath,
                'completed_at' => now()
            ]);

            // 面接完了メールを送信
            if ($entry->email) {
                // TODO: 面接完了メール送信機能を実装
                // Mail::to($entry->email)->send(new InterviewCompletedMail($entry));
            }

            return response()->json([
                'success' => true,
                'message' => '面接動画を送信しました。'
            ]);

        } catch (\Exception $e) {
            Log::error("送信処理エラー: " . $e->getMessage());
            Log::error("エラー発生場所: " . $e->getFile() . ":" . $e->getLine());
            Log::error("スタックトレース: " . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => '送信に失敗しました: ' . $e->getMessage()
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

            // 録り直し回数を増加
            $entry->update([
                'retake_count' => $retakeCount + 1,
                'status' => 'recording'
            ]);

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
     * 動画ファイルを配信（Range リクエスト対応）
     */
    public function serveVideo($filename)
    {
        $path = storage_path('app/public/interviews/' . $filename);

        if (!file_exists($path)) {
            abort(404, 'Video file not found');
        }

        $mimeType = mime_content_type($path);
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
    public function complete()
    {
        return view('record.complete');
    }
}
