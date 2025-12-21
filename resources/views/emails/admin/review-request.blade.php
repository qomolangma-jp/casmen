<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>応募者の評価をお願いいたします</title>
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

        <p>応募者より録画面接の動画が届きました。<br>
        管理画面より動画を確認し、評価を行ってください。</p>

        <hr>
        <p>■ 応募者情報</p>
        <hr>
        <p>・応募者名<br>
        {{ $entry->name }}</p>

        <p>・録画完了日時<br>
        {{ $entry->completed_at ? $entry->completed_at->format('Y/m/d H:i') : '' }}</p>

        <hr>
        <p>■ 評価の手順</p>
        <hr>
        <p>1. 管理画面にログイン<br>
        2. 応募者一覧から対象者を選択<br>
        3. 動画を視聴し、評価を入力</p>

        <p>◆ 管理画面はこちら<br>
        <a href="https://casmen.jp/admin">https://casmen.jp/admin</a></p>

        <p>※ 動画の保存期間は30日間です。お早めにご確認ください。</p>

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
