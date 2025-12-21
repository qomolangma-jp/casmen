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
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Validator;

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
        $action = $request->input('action');

        $rules = [
            'name' => 'required|string|max:255',
        ];

        if ($action === 'send') {
            $rules['email'] = 'nullable|email|max:255';
            $rules['phone'] = 'nullable|string|regex:/^[0-9\-\+\(\)\s]+$/|max:20';
        }

        $validator = Validator::make($request->all(), $rules, [
            'name.required' => 'お名前は必須です。',
            'email.email' => '正しいメールアドレス形式で入力してください。',
            'phone.regex' => '正しい電話番号形式で入力してください。',
        ]);

        $validator->after(function ($validator) use ($request, $action) {
            if ($action === 'send' && empty($request->email) && empty($request->phone)) {
                $validator->errors()->add('contact_error', 'メールアドレスまたは電話番号のいずれかを入力してください。');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 一意なURLトークンを生成
        $token = Str::random(32);
        $interviewUrl = url("/record?token={$token}");

        // Entryテーブルに保存
        try {
            $entry = new Entry();
            $entry->user_id = Auth::id();
            $entry->name = $request->name;
            $entry->email = $request->email;
            $entry->tel = $request->phone;
            $entry->interview_url = $interviewUrl;
            $entry->interview_uuid = $token;
            $entry->status = 'url_issued';
            $entry->save();
        } catch (\Exception $e) {
            Log::error('Entry creation failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'データ保存に失敗しました: ' . $e->getMessage()])->withInput();
        }

        if ($action === 'send') {
            $sentMethod = '';
            $errorMsg = null;

            if ($request->email) {
                // Send Email
                try {
                    Mail::to($request->email)->send(new InterviewLinkMail($entry, $interviewUrl));
                    $sentMethod = 'email';
                } catch (\Exception $e) {
                    Log::error('Email send failed: ' . $e->getMessage());
                    $errorMsg = 'メール送信に失敗しました。';
                }
            } elseif ($request->phone) {
                // Send SMS
                try {
                    $this->sendSms($request->phone, $interviewUrl, $entry->user->shop_name ?? 'CASMEN');
                    $sentMethod = 'sms';
                } catch (\Exception $e) {
                    Log::error('SMS send failed: ' . $e->getMessage());
                    $errorMsg = 'SMS送信に失敗しました。';
                }
            }

            if ($errorMsg) {
                 return redirect()->back()->withErrors(['error' => $errorMsg])->withInput();
            }

            return redirect()->route('admin.link.create')->with([
                'success_action' => 'send',
                'interview_url' => $interviewUrl
            ]);

        } else {
            // Issue only
            return redirect()->route('admin.link.create')->with([
                'success_action' => 'issue',
                'interview_url' => $interviewUrl
            ]);
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
}
