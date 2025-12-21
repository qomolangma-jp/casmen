<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>応募者へ不採用通知を送信しました</title>
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

        <p>応募者の方へ不採用通知を送信しました。</p>

        <hr>
        <p>■ 応募者情報</p>
        <hr>
        <p>・応募者名<br>
        {{ $entry->name }}</p>

        <p>・通知送信日時<br>
        {{ now()->format('Y/m/d H:i') }}</p>

        <hr>

        <p>不採用通知を送信した応募者は、管理画面の応募者一覧にて<br>
        ステータスが「不採用」として表示されます。</p>

        <p>応募者データは、録画完了日より30日経過すると<br>
        自動で削除されます。</p>

        <p>◆ 管理画面はこちら<br>
        <a href="https://casmen.jp/admin">https://casmen.jp/admin</a></p>

        <hr>

        <p>操作方法やご不明点がございましたら、お気軽にお問い合わせください。</p>

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
