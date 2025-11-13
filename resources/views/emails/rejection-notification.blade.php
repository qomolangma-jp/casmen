<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>面接結果のご連絡</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, 'Hiragino Kaku Gothic ProN', 'Hiragino Sans', Meiryo, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #6B7280;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .highlight {
            background-color: #fef3f2;
            padding: 15px;
            border-left: 4px solid #EF4444;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>面接結果のご連絡</h1>
    </div>

    <div class="content">
        <p>{{ $candidateName }} 様</p>

        <p>この度は、弊社の求人にご応募いただき、また貴重なお時間を面接にお使いいただき、誠にありがとうございました。</p>

        <div class="highlight">
            <p>慎重に検討させていただいた結果、今回は残念ながらご期待に沿えない結果となりました。</p>
        </div>

        <p>{{ $candidateName }}様の経験やスキルは素晴らしく、面接でお話しいただいた内容も大変興味深いものでした。しかしながら、今回の募集要項との適合性や、他の候補者との比較を総合的に判断した結果、このような決定に至りました。</p>

        <p>この度は、ご希望に添えずに大変申し訳ございません。</p>

        <p>なお、今回の結果は今後のキャリアや能力を否定するものでは決してありません。{{ $candidateName }}様の今後のご活躍を心よりお祈り申し上げます。</p>

        <p>また、機会がございましたら、ぜひ弊社の別の求人もご検討いただければと思います。</p>

        <p>末筆ながら、{{ $candidateName }}様のますますのご活躍とご健康をお祈り申し上げます。</p>

        <p>
            敬具<br>
            採用担当チーム
        </p>
    </div>

    <div class="footer">
        <p>このメールは自動送信されています。返信はご遠慮ください。</p>
        <p>ご質問やお問い合わせは、採用担当までお電話またはメールにてご連絡ください。</p>
    </div>
</body>
</html>
