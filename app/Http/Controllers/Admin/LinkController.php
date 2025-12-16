<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Mail\InterviewLinkMail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|regex:/^[0-9\-\+\(\)\s]+$/|max:20',
        ], [
            'name.required' => 'お名前は必須です。',
            'email.email' => '正しいメールアドレス形式で入力してください。',
            'phone.regex' => '正しい電話番号形式で入力してください。',
        ]);

        // 一意なURLトークンを生成
        $token = Str::random(32);

        // 面接URLを生成
        $interviewUrl = url("/record?token={$token}");

        // マスター設定による有効期限（デフォルト2週間）
        $expirationDays = config('app.interview_url_expiration_days', 14);
        $expiresAt = now()->addDays($expirationDays);


        // デバッグ: 保存前の状態確認
        Log::info('保存前の状態確認', [
            'user_id' => Auth::id(),
            'is_authenticated' => Auth::check(),
            'name' => $request->name,
            'email' => $request->email,
            'tel' => $request->phone,
            'interview_url' => $interviewUrl,
            'interview_uuid' => $token,
            'token_length' => strlen($token),
        ]);

        // Entryテーブルに保存
        try {
            // 個別設定方式で保存を試行
            $entry = new Entry();
            $entry->user_id = Auth::id();
            $entry->name = $request->name;
            $entry->email = $request->email;
            $entry->tel = $request->phone;
            $entry->interview_url = $interviewUrl;
            $entry->interview_uuid = $token;
            $entry->status = 'url_issued';
            $saved = $entry->save();

            Log::info('Entry save result', [
                'saved' => $saved,
                'entry_id' => $entry->entry_id
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


        // メール送信
        $mailStatus = 'メールアドレス未入力のため送信していません。';
        if ($request->email) {
            try {
                Mail::to($request->email)->send(new InterviewLinkMail($entry, $interviewUrl));
                Log::info('面接URLメール送信成功', [
                    'entry_id' => $entry->entry_id,
                    'email' => $request->email
                ]);
                $mailStatus = 'メールを送信しました。';
            } catch (\Exception $e) {
                Log::error('面接URLメール送信失敗: ' . $e->getMessage(), [
                    'entry_id' => $entry->entry_id,
                    'email' => $request->email
                ]);
                $mailStatus = 'メール送信に失敗しました: ' . $e->getMessage();
            }
        }

        // 成功メッセージを設定
        $successMessage = "面接URLが正常に発行されました。応募者: {$request->name} - {$mailStatus}";

        return redirect()->route('admin.link.create')
            ->with('success', $successMessage)
            ->with('interview_url', $interviewUrl)
            ->with('applicant_info', [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'expires_at' => $expiresAt->format('Y年m月d日 H:i'),
                'entry_id' => $entry->entry_id,
                'mail_status' => $mailStatus,
            ]);
    }
}
