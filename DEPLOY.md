# デプロイ手順書

## 1. 本番環境用設定ファイルの準備

### .env.production を .env にリネーム
本番サーバーで `.env.production` を `.env` にリネームしてください。

### APP_KEY の生成
```bash
php artisan key:generate
```

### データベース設定
```
DB_CONNECTION=mysql
DB_HOST=your_db_host
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### メール設定（本番用SMTP）
```
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="らくらくセルフ面接"
```

## 2. アップロード手順

### ファイル構成
```
public_html/
├── public/ (Laravelのpublicフォルダの内容)
│   ├── index.php
│   ├── build/
│   └── storage/
├── laravel/ (その他のLaravelファイル)
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   └── vendor/
└── .env
```

### index.php の修正
public/index.php のパスを修正：
```php
require_once __DIR__.'/../laravel/vendor/autoload.php';
$app = require_once __DIR__.'/../laravel/bootstrap/app.php';
```

## 3. データベースのセットアップ

### マイグレーション実行
```bash
php artisan migrate --force
```

### シーダー実行（データがある場合）
```bash
php artisan db:seed --force
```

## 4. 本番環境用最適化

### キャッシュ生成
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### ストレージリンク作成
```bash
php artisan storage:link
```

## 5. ディレクトリ権限設定

### 書き込み権限
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

## 6. 必要な要件

### PHP バージョン
- PHP 8.1 以上

### PHP 拡張機能
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML
- GD (画像処理用)

### データベース
- MySQL 5.7 以上 または MariaDB 10.3 以上

## 7. セキュリティ設定

### .htaccess の追加（public以外へのアクセス拒否）
```apache
# Laravel root directory
<Files .env>
    Order allow,deny
    Deny from all
</Files>

<Files composer.json>
    Order allow,deny
    Deny from all
</Files>

<Files composer.lock>
    Order allow,deny
    Deny from all
</Files>

<Files package.json>
    Order allow,deny
    Deny from all
</Files>
```

## 8. 動作確認項目

### 基本機能
- [ ] トップページの表示
- [ ] 面接URLでのアクセス
- [ ] カメラ機能の動作
- [ ] 録画機能の動作
- [ ] 動画アップロード機能
- [ ] メール送信機能

### 管理機能
- [ ] ログイン機能
- [ ] 面接URL発行
- [ ] 応募者管理
- [ ] マスター管理

## 9. トラブルシューティング

### よくある問題
1. **500エラー**: storage/logs/laravel.log を確認
2. **権限エラー**: storage/, bootstrap/cache/ の権限確認
3. **DB接続エラー**: .env のDB設定確認
4. **メール送信エラー**: MAIL設定とSMTP認証確認

### デバッグモード（開発時のみ）
```
APP_DEBUG=true
LOG_LEVEL=debug
```
