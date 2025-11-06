<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Http\Request;

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

        return view('record.index', [
            'token' => $token,
            'isValidToken' => $isValidToken,
            'entry' => $entry,
            'errorMessage' => $errorMessage
        ]);
    }

    /**
     * 面接動画をアップロード
     */
    public function upload(Request $request)
    {
        $request->validate([
            'video' => 'required|file|mimetypes:video/webm,video/mp4|max:102400', // 100MB
            'token' => 'required|string'
        ]);

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
                'message' => 'この面接は既に完了しています。'
            ], 400);
        }

        try {
            // 動画ファイルを保存
            $videoFile = $request->file('video');
            $fileName = 'interview_' . $entry->entry_id . '_' . time() . '.webm';
            $path = $videoFile->storeAs('interviews', $fileName, 'public');

            // Entryレコードを更新
            $entry->update([
                'status' => 'completed',
                'video_path' => $path,
                'completed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => '面接動画がアップロードされました。'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'アップロードに失敗しました: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 面接完了ページ
     */
    public function complete()
    {
        return view('record.complete');
    }
}
