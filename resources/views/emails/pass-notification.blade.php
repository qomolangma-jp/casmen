<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>合格通知</title>
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
            background-color: #4CAF50;
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
            background-color: #e8f5e8;
            padding: 15px;
            border-left: 4px solid #4CAF50;
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
        <h1>🎉 合格通知</h1>
    </div>

    <div class="content">
        <p>{{ $candidateName }} 様</p>

        <p>この度は、弊社の求人にご応募いただき、誠にありがとうございました。</p>

        <div class="highlight">
            <p><strong>面接の結果、あなたを採用させていただくことになりました。おめでとうございます！</strong></p>
        </div>

        <p>あなたの経験やスキル、そして面接での印象を総合的に評価した結果、弊社チームの一員として活躍していただけると確信しております。</p>

        <h3>今後の流れについて</h3>
        <ul>
            <li>入社手続きに関する詳細については、別途人事担当者よりご連絡いたします</li>
            <li>必要書類や入社日程につきましても、追って調整させていただきます</li>
            <li>ご質問がございましたら、いつでもお気軽にお問い合わせください</li>
        </ul>

        <p>私たちは、{{ $candidateName }}様をチームにお迎えできることを心より楽しみにしております。</p>

        <p>改めて、ご応募いただき、ありがとうございました。</p>

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
