<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>録画面接のご案内</title>
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
        <p>{{ $entry->name }} 様</p>

        <p>この度は、【{{ $companyName }}】にご応募いただき、誠にありがとうございます。<br>
        採用担当です。</p>

        <p>次の選考ステップとして、録画面接（セルフ面接）をお願いしております。<br>
        お手持ちのスマートフォンやPCから、ご都合の良い時間にご回答ください。</p>

        <p>所要時間は5分〜10分程度です。</p>

        <p>▼ 録画面接はこちらから<br>
        <a href="{{ $interviewUrl }}">{{ $interviewUrl }}</a></p>

        <p>※ 上記URLの有効期限は、本メール受信後【1週間】となります。<br>
        お早めにご対応いただけますと幸いです。</p>

        <hr>
        <p>【録画面接の流れ】<br>
        1. 上記URLにアクセス<br>
        2. 画面の案内に従って、質問に動画で回答<br>
        3. すべての回答が完了したら送信</p>

        <p>ご不明な点がございましたら、お気軽にお問い合わせください。</p>
        <hr>

        <p>{{ $entry->name }} 様の素敵な一面を知れることを楽しみにしております。<br>
        引き続き、よろしくお願いいたします。</p>

        <div class="footer">
            <p>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
            【{{ $companyName }}】<br>
            Powered by CASMEN<br>
            <a href="https://casmen.jp/">https://casmen.jp/</a></p>
        </div>
    </div>
</body>
</html>
