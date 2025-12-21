<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アルバイトに関するお知らせ</title>
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
        <p>{{ $candidateName }} 様</p>

        <p>この度は、数ある求人の中から<br>
        {{ $shopName }}にご応募いただき、誠にありがとうございます。</p>

        <p>お送りいただきました応募情報を拝見し、<br>
        慎重に選考を行いました結果、<br>
        誠に残念ながら今回は採用を見送らせていただくことになりました。</p>

        <p>ご希望に添いかねる結果となり、大変恐縮ではございますが、<br>
        何卒ご了承くださいますようお願い申し上げます。</p>

        <p>多数の求人の中から、当店にご関心をお寄せいただきましたこと、<br>
        心より感謝申し上げます。</p>

        <p>末筆ではございますが、{{ $candidateName }} 様の<br>
        今後のご健勝とご活躍をお祈り申し上げます。</p>

        <div class="footer">
            <p>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
            【もえなび！】<br>
            <a href="https://moe-navi.jp/">https://moe-navi.jp/</a></p>
        </div>
    </div>
</body>
</html>
