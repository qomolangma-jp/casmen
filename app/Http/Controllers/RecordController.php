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
}
