<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>応募者への面接URL送信をお願いいたします</title>
    <style>
        body {
            font-family: sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <p>{{ $entry->user->shop_name ?? $entry->user->name }} 様</p>

        <p>応募者の登録は完了しましたが、メールアドレスまたは電話番号が<br>
        登録されていないため、面接URLを自動送信できませんでした。</p>

        <p>お手数ですが、下記の面接URLをコピーし、応募者へ直接ご連絡をお願いいたします。</p>

        <hr>
        <p>■ 応募者情報</p>
        <hr>
        <p>・応募者名<br>
        {{ $entry->name }}</p>

        <p>・登録された連絡先<br>
        （なし）</p>

        <p>・登録日時<br>
        {{ $entry->created_at->format('Y/m/d H:i') }}</p>

        <hr>
        <p>■ 応募者にお送りいただく面接URL</p>
        <hr>
        <p>{{ $manual_interview_url ?? $entry->interview_url }}</p>

        <p>※ 上記URLをコピーして、店舗様より応募者へ送信してください。</p>

        <hr>
        <p>■ 録画面接について</p>
        <hr>
        <p>応募者が録画面接を完了すると、管理画面に<br>
        「評価待ち」のステータスが表示されます。</p>

        <p>◆ 管理画面はこちら ◆<br>
        <a href="https://casmen.jp/admin">https://casmen.jp/admin</a></p>

        <p>ログイン後、応募者一覧よりご確認いただけます。</p>

        <hr>

        <p>操作方法や何か分からない点がございましたら、お気軽にお問い合わせください。</p>

        <p>◆お問い合わせはこちら◆<br>
        <a href="mailto:support@casmen.jp">support@casmen.jp</a></p>

        <div class="footer">
            <p>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
            【CASMEN】<br>
            <a href="https://casmen.jp/">https://casmen.jp/</a></p>
        </div>
    </div>
</body>
</html>
