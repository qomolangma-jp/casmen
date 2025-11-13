<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>面接URLのお知らせ</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .message {
            margin-bottom: 25px;
            line-height: 1.8;
        }
        .interview-info {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .interview-info h3 {
            margin: 0 0 15px 0;
            color: #495057;
            font-size: 16px;
        }
        .interview-info p {
            margin: 8px 0;
            color: #6c757d;
        }
        .interview-url {
            background-color: #e3f2fd;
            border: 2px solid #2196f3;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        .interview-url h3 {
            margin: 0 0 15px 0;
            color: #1976d2;
            font-size: 18px;
        }
        .url-link {
            display: inline-block;
            background-color: #2196f3;
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: background-color 0.3s;
            word-break: break-all;
        }
        .url-link:hover {
            background-color: #1976d2;
        }
        .instructions {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 20px;
            margin: 25px 0;
        }
        .instructions h3 {
            margin: 0 0 15px 0;
            color: #856404;
            font-size: 16px;
        }
        .instructions ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .instructions li {
            margin: 8px 0;
            color: #856404;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }
        .footer p {
            margin: 5px 0;
            color: #6c757d;
            font-size: 14px;
        }
        .footer .company {
            font-weight: 600;
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎯 面接URLのお知らせ</h1>
        </div>

        <div class="content">
            <div class="greeting">
                {{ $entry->name }} 様
            </div>

            <div class="message">
                この度は、弊社の面接にご応募いただき、誠にありがとうございます。<br>
                下記の面接用URLをお送りいたします。
            </div>

            <div class="interview-info">
                <h3>📋 面接情報</h3>
                <p><strong>お名前:</strong> {{ $entry->name }}</p>
                <p><strong>メールアドレス:</strong> {{ $entry->email }}</p>
                <p><strong>電話番号:</strong> {{ $entry->tel ?? '未登録' }}</p>
                @if($entry->memo)
                <p><strong>備考:</strong> {{ $entry->memo }}</p>
                @endif
            </div>

            <div class="interview-url">
                <h3>🔗 面接用URL</h3>
                <p>下記のURLをクリックして面接を開始してください：</p>
                <a href="{{ $interviewUrl }}" class="url-link" target="_blank">
                    面接を開始する
                </a>
                <p style="margin-top: 15px; font-size: 12px; color: #666;">
                    URL: {{ $interviewUrl }}
                </p>
            </div>

            <div class="instructions">
                <h3>📝 面接について</h3>
                <ul>
                    <li>面接はビデオ録画形式で行われます</li>
                    <li>カメラとマイクの使用許可が必要です</li>
                    <li>各質問に対して1分間でお答えください</li>
                    <li>面接URLの有効期限は発行から2週間です</li>
                    <li>一度完了した面接URLは再利用できません</li>
                </ul>
            </div>

            <div class="message">
                何かご不明な点がございましたら、お気軽にお問い合わせください。<br>
                面接の完了をお待ちしております。
            </div>
        </div>

        <div class="footer">
            <p class="company">CASMEN 採用チーム</p>
            <p>このメールは自動送信されています。返信はご遠慮ください。</p>
        </div>
    </div>
</body>
</html>
