<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>面接URLの再送をお願いいたします</title>
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

        <p>応募者の方より、録画面接の動画が届いておりません。</p>

        <p>応募者へ面接URLを再送いただけますようお願いいたします。</p>

        <hr>
        <p>■ 応募者情報</p>
        <hr>
        <p>・応募者名<br>
        {{ $entry->name }}</p>

        <p>・登録日時<br>
        {{ $entry->created_at->format('Y/m/d H:i') }}</p>

        <hr>
        <p>■ 面接URLの再送について</p>
        <hr>

        <p>以下のいずれかの方法で、応募者へ再度ご案内いただけます。</p>

        <p>① 面接URLを再送する<br>
        応募者詳細ページ内の「面接URLを再送」をご利用ください。</p>

        <p>② URLをコピーして直接送る<br>
        面接URLをコピーし、店舗様より応募者へ直接ご連絡ください。</p>

        <p>◆ 応募者詳細URLはこちら<br>
        <a href="{{ $applicant_detail_url ?? '#' }}">{{ $applicant_detail_url ?? '' }}</a></p>

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
