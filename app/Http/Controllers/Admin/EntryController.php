<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Models\EntryInterview;
use App\Mail\PassNotification;
use App\Mail\RejectionNotification;
use App\Mail\InterviewLinkMail;
use App\Mail\AdminApplicantNotificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client;

class EntryController extends Controller
{
    /**
     * サイトマップ ID: 9 - 応募者一覧
     * 全応募者データを取得して一覧表示
     */
    public function index()
    {
        // 評価待ちの応募者（全件取得）
        $waitingEntries = Entry::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->whereNull('decision_at')
            ->orderBy('entry_id', 'desc')
            ->get();

        // 全応募者（ページネーション）
        $entries = Entry::where('user_id', Auth::id())
            ->orderBy('entry_id', 'desc')
            ->paginate(10);

        return view('admin.entry.index', compact('entries', 'waitingEntries'));
    }

    /**
     * サイトマップ ID: 10 - 応募者詳細
     * 指定された応募者の詳細情報を表示
     */
    public function show($id)
    {
        $entry = Entry::where('user_id', Auth::id())->findOrFail($id);

        // 質問ごとの動画を取得
        $entryInterviews = EntryInterview::where('entry_id', $entry->entry_id)
                                         ->with('question')
                                         ->orderBy('question_id')
                                         ->get();

        // JavaScript用に質問データをマッピング
        $interviewQuestionsData = $entryInterviews->map(function($interview) {
            $videoUrl = config('filesystems.default') === 's3'
                ? route('record.video', ['filename' => basename($interview->file_path)])
                : route('record.video', ['filename' => basename($interview->file_path)]);

            Log::info("Admin動画URL生成: file_path={$interview->file_path}, video_url={$videoUrl}");

            return [
                'question' => $interview->question->q ?? '質問なし',
                'file_path' => $interview->file_path,
                'video_url' => $videoUrl,
            ];
        });

        return view('admin.entry.show', compact('entry', 'entryInterviews', 'interviewQuestionsData'));
    }

    /**
     * 応募者専用面接URL表示
     * 指定された応募者の面接URL情報を表示
     */
    public function interview($id)
    {
        $entry = Entry::where('user_id', Auth::id())->findOrFail($id);
        return view('admin.entry.interview', compact('entry'));
    }

    /**
     * 応募者のステータスを「不採用」に更新
     */
    public function reject(Request $request, $id)
    {
        try {
            $entry = Entry::where('user_id', Auth::id())->findOrFail($id);

            // ステータスを不採用に更新
            $entry->update([
                'status' => 'rejected',
                'decision_at' => now()
            ]);

            Log::info("応募者不採用処理: entry_id={$id}, name={$entry->name}");

            // 不採用通知メールを送信（メールアドレスがある場合）
            if ($entry->email) {
                Mail::to($entry->email)->send(new RejectionNotification($entry));
                Log::info("不採用通知メール送信: email={$entry->email}");
            }

            // 管理者へ不採用通知送信のお知らせ
            if ($entry->user && $entry->user->email) {
                try {
                    Mail::to($entry->user->email)->send(new AdminApplicantNotificationMail($entry, 'rejection_sent'));
                    Log::info("管理者へ不採用通知送信のお知らせメール送信: admin_email={$entry->user->email}");
                } catch (\Exception $e) {
                    Log::error("管理者へ不採用通知送信のお知らせメール送信失敗: " . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => '応募者を不採用にし、メール通知を送信しました。'
            ]);

        } catch (\Exception $e) {
            Log::error("不採用処理エラー: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '処理中にエラーが発生しました: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 応募者のステータスを「通過」に更新
     */
    public function pass(Request $request, $id)
    {
        try {
            $entry = Entry::where('user_id', Auth::id())->findOrFail($id);

            // ステータスを通過に更新
            $entry->update([
                'status' => 'passed',
                'decision_at' => now()
            ]);

            Log::info("応募者合格処理: entry_id={$id}, name={$entry->name}");

            // 合格通知メールを送信（メールアドレスがある場合）
            if ($entry->email) {
                Mail::to($entry->email)->send(new PassNotification($entry));
                Log::info("合格通知メール送信: email={$entry->email}");
            }

            return response()->json([
                'success' => true,
                'message' => '応募者を合格にし、メール通知を送信しました。'
            ]);

        } catch (\Exception $e) {
            Log::error("合格処理エラー: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '処理中にエラーが発生しました: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 面接URLを再送する
     */
    public function resend(Request $request, $id)
    {
        try {
            $entry = Entry::where('user_id', Auth::id())->findOrFail($id);

            // 再送回数チェック (最大3回)
            if (($entry->retake_count ?? 0) >= 3) {
                return response()->json([
                    'success' => false,
                    'message' => '再送回数の上限（3回）に達しています。'
                ], 400);
            }

            // 面接URLの生成 (welcomeページへのリンク)
            $interviewUrl = route('record.welcome', ['token' => $entry->interview_uuid]);
            $sentMethod = '';

            if ($entry->email) {
                // メール送信
                Mail::to($entry->email)->send(new InterviewLinkMail($entry, $interviewUrl));
                $sentMethod = 'メール';
            } elseif ($entry->tel) {
                // SMS送信
                $this->sendSms($entry->tel, $interviewUrl, $entry->user->shop_name ?? 'CASMEN');
                $sentMethod = 'SMS';
            } else {
                return response()->json([
                    'success' => false,
                    'message' => '応募者のメールアドレスまたは電話番号が登録されていません。'
                ], 400);
            }

            // 再送回数をインクリメント
            $entry->increment('retake_count');

            Log::info("面接URL再送({$sentMethod}): entry_id={$id}, email={$entry->email}, tel={$entry->tel}, count=" . ($entry->retake_count));

            // 管理者へURL送信完了のお知らせ
            if ($entry->user && $entry->user->email) {
                try {
                    Mail::to($entry->user->email)->send(new AdminApplicantNotificationMail($entry, 'url_sent'));
                    Log::info("管理者へURL送信完了通知メール送信: admin_email={$entry->user->email}");
                } catch (\Exception $e) {
                    Log::error("管理者へURL送信完了通知メール送信失敗: " . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => "面接URLを{$sentMethod}で再送しました。"
            ]);

        } catch (\Exception $e) {
            Log::error("面接URL再送エラー: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '処理中にエラーが発生しました: ' . $e->getMessage()
            ], 500);
        }
    }

    private function sendSms($to, $url, $shopName)
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from = config('services.twilio.from');

        if (!$sid || !$token || !$from) {
            throw new \Exception('Twilio credentials not configured.');
        }

        // E.164 formatting for Japan if needed (simple check)
        if (strpos($to, '0') === 0) {
            $to = '+81' . substr($to, 1);
        }

        $client = new Client($sid, $token);
        $message = "【{$shopName}】より\nらくらくセルフ面接のご案内です。\n24問の質問に答え、あなたの雰囲気を伝えることができます。\n下記URLより24時間いつでもスタートできます。\n{$url}";

        $client->messages->create(
            $to,
            [
                'from' => $from,
                'body' => $message
            ]
        );
    }

    /**
     * 動画に字幕を埋め込む（ローカルテスト用）
     */
    public function burnSubtitles(Request $request, $id)
    {
        try {
            $entry = Entry::where('user_id', Auth::id())->findOrFail($id);

            if (!$entry->video_path) {
                return back()->with('error', '動画ファイルがありません。');
            }

            $vttPath = str_replace('.webm', '.vtt', $entry->video_path);

            // VTTファイルの存在確認
            $disk = config('filesystems.default');
            $exists = $disk === 's3' ? \Illuminate\Support\Facades\Storage::disk('s3')->exists($vttPath) : file_exists(storage_path('app/public/' . $vttPath));

            if (!$exists) {
                 return back()->with('error', '字幕ファイルが見つかりません。');
            }

            // サービス呼び出し
            $service = new \App\Services\VideoProcessingService();

            // ローカル環境の場合は上書きせず、別ファイルとして保存
            $overwrite = !app()->isLocal();
            $resultPath = $service->burnSubtitles($entry->video_path, $vttPath, $overwrite);

            if ($resultPath) {
                if (!$overwrite) {
                    return back()->with('success', '字幕埋め込み完了。デバッグ用ファイル: ' . basename($resultPath));
                }
                return back()->with('success', '字幕の埋め込みが完了しました。');
            } else {
                return back()->with('error', '字幕の埋め込みに失敗しました。ログを確認してください。');
            }
        } catch (\Exception $e) {
            Log::error("字幕埋め込みエラー: " . $e->getMessage());
            return back()->with('error', 'エラーが発生しました: ' . $e->getMessage());
        }
    }
}

