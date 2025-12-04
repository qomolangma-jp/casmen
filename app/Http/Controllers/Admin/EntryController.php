<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Mail\PassNotification;
use App\Mail\RejectionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EntryController extends Controller
{
    /**
     * サイトマップ ID: 9 - 応募者一覧
     * 全応募者データを取得して一覧表示
     */
    public function index()
    {
        $entries = Entry::orderBy('entry_id', 'desc')->get();
        return view('admin.entry.index', compact('entries'));
    }

    /**
     * サイトマップ ID: 10 - 応募者詳細
     * 指定された応募者の詳細情報を表示
     */
    public function show($id)
    {
        $entry = Entry::findOrFail($id);
        return view('admin.entry.show', compact('entry'));
    }

    /**
     * 応募者専用面接URL表示
     * 指定された応募者の面接URL情報を表示
     */
    public function interview($id)
    {
        $entry = Entry::findOrFail($id);
        return view('admin.entry.interview', compact('entry'));
    }

    /**
     * 応募者のステータスを「不採用」に更新
     */
    public function reject(Request $request, $id)
    {
        try {
            $entry = Entry::findOrFail($id);

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
            $entry = Entry::findOrFail($id);

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
}
