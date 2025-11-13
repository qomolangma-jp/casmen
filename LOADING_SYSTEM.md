# 共通ローディング機能

このプロジェクトには、すべてのPOST処理時に自動的にローディング表示とボタン無効化を行う共通機能が実装されています。

## 🚀 特徴

- **自動検出**: すべてのPOST形式のフォーム送信とfetchリクエストを自動で検出
- **ボタン無効化**: 処理中は全てのボタンを自動で無効化（重複送信防止）
- **視覚的フィードバック**: 美しいローディングオーバーレイとスピナー
- **カスタマイズ可能**: メッセージやスタイルの変更が簡単
- **レスポンシブ対応**: モバイルデバイスでも最適表示
- **部分的無効化**: 特定の要素でローディングを無効にすることが可能

## 📦 導入済みファイル

```
public/
├── css/loading-manager.css    # ローディング機能のスタイル
└── js/loading-manager.js      # ローディング機能のJavaScript

resources/views/layouts/
├── app.blade.php             # メインレイアウト（更新済み）
├── guest.blade.php           # ゲストレイアウト（更新済み）
├── master.blade.php          # 管理者レイアウト（更新済み）
└── interview.blade.php       # 面接レイアウト（更新済み）
```

## 🎯 使用方法

### 1. 基本的な使用（推奨）

特に設定は不要です。すべてのPOSTリクエストで自動的にローディングが表示されます。

```html
<!-- 通常のフォーム（自動ローディング有効） -->
<form method="POST" action="/submit">
    @csrf
    <button type="submit">送信する</button>
</form>
```

```javascript
// fetchリクエスト（自動ローディング有効）
const response = await fetch('/api/data', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({ data: 'example' })
});
```

### 2. ローディングを無効にしたい場合

`data-no-loading` 属性を追加します。

```html
<!-- フォーム全体でローディング無効 -->
<form method="POST" action="/submit" data-no-loading>
    @csrf
    <button type="submit">送信する</button>
</form>

<!-- 特定のボタンのみローディング無効 -->
<button data-no-loading onclick="quickAction()">
    クイックアクション
</button>
```

### 3. 手動でローディングを制御

```javascript
// ローディング開始
showLoading('データを処理中...');

// 何らかの処理
await processData();

// ローディング終了
hideLoading();
```

### 4. カスタムメッセージ

```javascript
// デフォルトメッセージを変更
showLoading('ファイルをアップロード中...');
showLoading('データベースを更新中...');
showLoading('メールを送信中...');
```

## 🎨 カスタマイズ

### CSS変数を使用したカスタマイズ

```css
/* public/css/loading-manager.css に追加 */
:root {
    --loading-bg-color: rgba(0, 0, 0, 0.5);
    --loading-spinner-color: #3498db;
    --loading-text-color: #333;
}
```

### JavaScriptでの設定変更

```javascript
// グローバル設定
window.LoadingManager.defaultMessage = 'お待ちください...';
window.LoadingManager.timeout = 15000; // 15秒でタイムアウト
```

## 📱 レスポンシブ対応

- モバイル用に最適化されたサイズとレイアウト
- タッチデバイスでの操作性を考慮
- 画面サイズに応じた自動調整

## ♿ アクセシビリティ

- `prefers-reduced-motion` に対応
- キーボードナビゲーション対応
- スクリーンリーダー対応

## 🔧 トラブルシューティング

### ローディングが表示されない場合

1. ブラウザの開発者ツールでJavaScriptエラーを確認
2. CSSファイルが正しく読み込まれているか確認
3. `data-no-loading` 属性が意図せず設定されていないか確認

### ローディングが終了しない場合

```javascript
// 強制的にローディングを終了
hideLoading();

// または直接DOM要素を非表示
document.getElementById('global-loading-overlay').style.display = 'none';
```

### 既存のJavaScriptとの競合

```javascript
// 既存のローディング機能を無効化
window.LoadingManager = null;
```

## 🧪 テスト

### ローディング機能のテスト

```html
<!-- テスト用ボタン -->
<button onclick="testLoading()">ローディングテスト</button>

<script>
function testLoading() {
    showLoading('テスト中...');
    setTimeout(() => {
        hideLoading();
        alert('テスト完了！');
    }, 3000);
}
</script>
```

## 🎯 実装例

### 面接動画送信

```javascript
document.getElementById('submitAllBtn').addEventListener('click', async () => {
    if (!confirm('面接動画を送信しますか？')) return;

    // 自動的にローディングが表示される
    try {
        const response = await fetch('/record/submit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ token: interviewToken })
        });

        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            window.location.href = '/complete';
        }
    } catch (error) {
        console.error('Error:', error);
        alert('送信に失敗しました。');
    }
    // 自動的にローディングが非表示になる
});
```

### 管理者の採用/不採用処理

```javascript
document.getElementById('passBtn').addEventListener('click', async () => {
    if (!confirm('この応募者を合格にしますか？')) return;

    // 自動的にローディングが表示される
    try {
        const response = await fetch(`/admin/entry/${entryId}/pass`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
        alert('処理に失敗しました。');
    }
    // 自動的にローディングが非表示になる
});
```

## 📝 更新履歴

- **v1.0.0** (2025-11-13): 初期実装
  - 自動POST検出機能
  - ローディングオーバーレイ
  - ボタン無効化機能
  - レスポンシブ対応

## 💡 今後の改善予定

- [ ] プログレスバー機能
- [ ] タイムアウト時の自動リトライ
- [ ] 複数リクエストの同時管理
- [ ] カスタムアニメーション対応
