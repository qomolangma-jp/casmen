<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LinkController extends Controller
{
    /**
     * 面接URL発行フォームを表示
     */
    public function create()
    {
        return view('admin.link.create');
    }

    /**
     * 面接URLを生成・保存
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|regex:/^[0-9\-\+\(\)\s]+$/|max:20',
        ], [
            'name.required' => 'お名前は必須です。',
            'email.required' => 'メールアドレスは必須です。',
            'email.email' => '正しいメールアドレス形式で入力してください。',
            'phone.required' => '電話番号は必須です。',
            'phone.regex' => '正しい電話番号形式で入力してください。',
        ]);

        // 一意なURLトークンを生成
        $token = Str::random(32);

        // 面接URLを生成
        $interviewUrl = url("/record?token={$token}");

        // マスター設定による有効期限（デフォルト2週間）
        $expirationDays = config('app.interview_url_expiration_days', 14);
        $expiresAt = now()->addDays($expirationDays);


        // Entryテーブルに保存
        try {
            $entry = Entry::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'email' => $request->email,
                'tel' => $request->phone,
                'interview_url' => $interviewUrl,
                'interview_uuid' => $token,
                'status' => 'url_issued', // URL発行済み
            ]);
        } catch (\Exception $e) {
            // エラー詳細をログに記録
            Log::error('Entry creation failed: ' . $e->getMessage());
            Log::error('Data: ' . json_encode([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'email' => $request->email,
                'tel' => $request->phone,
                'interview_url' => $interviewUrl,
                'interview_uuid' => $token,
                'status' => 'url_issued',
            ]));

            return redirect()->route('admin.link.create')
                ->withErrors(['error' => 'データ保存に失敗しました: ' . $e->getMessage()])
                ->withInput();
        }

        // 成功メッセージを設定
        $successMessage = "面接URLが正常に発行されました。応募者: {$request->name}";

        return redirect()->route('admin.link.create')
            ->with('success', $successMessage)
            ->with('interview_url', $interviewUrl)
            ->with('applicant_info', [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'expires_at' => $expiresAt->format('Y年m月d日 H:i'),
                'entry_id' => $entry->entry_id,
            ]);
    }
}
