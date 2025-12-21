<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>応募者へ面接URLを送信しました</title>
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
        <p>{{ $entry->user->name }} 様</p>

        <p>応募者の登録が完了し、面接URLを応募者へ送信いたしました。</p>

        <p>応募者が録画面接を行うと、管理画面に動画が表示されますので、<br>
        ご確認をお願いいたします。</p>

        <hr>
        <p>■ 応募者情報</p>
        <hr>
        <p>・応募者名<br>
        {{ $entry->name }}</p>

        <p>・メールアドレス または 電話番号<br>
        {{ $entry->email ?? $entry->tel }}</p>

        <p>・送信日時<br>
        {{ now()->format('Y/m/d H:i') }}</p>

        <hr>
        <p>■ 録画面接の確認方法</p>
        <hr>
        <p>応募者が録画面接を完了すると、管理画面に<br>
        「評価待ち」のステータスが表示されます。</p>

        <p>◆ 管理画面はこちら ◆<br>
        <a href="https://casmen.jp/admin">https://casmen.jp/admin</a></p>

        <p>ログイン後、応募者一覧よりご確認ください。</p>

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
