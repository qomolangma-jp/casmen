#!/bin/bash

# 本番環境デプロイスクリプト
echo "=== Laravel本番環境セットアップ開始 ==="

# 1. Composerの依存関係インストール（本番用）
echo "Composerパッケージをインストール中..."
composer install --optimize-autoloader --no-dev

# 2. アプリケーションキー生成
echo "アプリケーションキーを生成中..."
php artisan key:generate --force

# 3. データベースマイグレーション
echo "データベースマイグレーション実行中..."
php artisan migrate --force

# 4. シーダー実行
echo "初期データを投入中..."
php artisan db:seed --force

# 5. ストレージリンク作成
echo "ストレージリンクを作成中..."
php artisan storage:link

# 6. キャッシュ最適化
echo "キャッシュを最適化中..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. 権限設定
echo "ディレクトリ権限を設定中..."
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

echo "=== デプロイ完了 ==="
echo "動作確認を行ってください。"
