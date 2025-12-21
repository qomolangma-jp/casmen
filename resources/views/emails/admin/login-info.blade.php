<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理画面にログインを行ってください</title>
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
        <p>{{ $user->name }} 様</p>

        <p>CASMENの管理画面アカウントを発行いたしました。<br>
        以下の情報でログインを行ってください。</p>

        <hr>
        <p>■ ログイン情報</p>
        <hr>
        <p>・ログインURL<br>
        <a href="https://casmen.jp/admin/login">https://casmen.jp/admin/login</a></p>

        <p>・メールアドレス<br>
        {{ $user->email }}</p>

        <p>・パスワード<br>
        {{ $password }}</p>

        <p>※ セキュリティのため、初回ログイン後にパスワードの変更をお勧めします。</p>

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
