{{--
    共通ローディング機能の使用方法と設定

    1. 自動ローディング（デフォルト）
    - すべてのPOSTリクエスト（form送信、fetch）で自動的にローディングが表示されます
    - 特に設定は不要です

    2. ローディングを無効にしたい場合
    - フォーム要素に data-no-loading 属性を追加：
      <form method="POST" data-no-loading>
    - ボタンに data-no-loading 属性を追加：
      <button data-no-loading>
    - 親要素に属性があれば、子要素も無効になります

    3. 手動でローディングを制御
    - JavaScript: showLoading('カスタムメッセージ');
    - JavaScript: hideLoading();

    4. カスタムローディングメッセージ
    - デフォルト: '処理中...'
    - フォーム送信: '送信中...'
    - カスタム: showLoading('データを保存中...');

    使用例は以下の通りです：
--}}

{{-- 通常のフォーム（自動ローディング有効） --}}
<form method="POST" action="/example">
    @csrf
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
        送信する
    </button>
</form>

{{-- ローディングを無効にしたいフォーム --}}
<form method="POST" action="/example" data-no-loading>
    @csrf
    <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded">
        送信する（ローディングなし）
    </button>
</form>

{{-- 手動制御の例 --}}
<button onclick="handleCustomAction()" class="bg-green-500 text-white px-4 py-2 rounded">
    カスタム処理
</button>

<script>
function handleCustomAction() {
    // カスタムローディング開始
    showLoading('データを処理中...');

    // 何らかの処理
    setTimeout(() => {
        // ローディング終了
        hideLoading();
        alert('処理完了！');
    }, 3000);
}

// fetch使用例（自動ローディング）
async function sendData() {
    try {
        const response = await fetch('/api/data', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ data: 'example' })
        });

        const result = await response.json();
        console.log(result);
    } catch (error) {
        console.error('Error:', error);
    }
    // ローディングは自動的に終了
}

// ローディングを無効にしたいfetch
async function sendDataWithoutLoading(button) {
    // ボタンに data-no-loading 属性を設定
    button.setAttribute('data-no-loading', '');

    try {
        const response = await fetch('/api/data', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ data: 'example' })
        });

        const result = await response.json();
        console.log(result);
    } catch (error) {
        console.error('Error:', error);
    }
}
</script>

{{-- スタイリング例 --}}
<style>
/* カスタムローディングボタンスタイル */
.custom-loading-btn {
    position: relative;
    transition: all 0.3s ease;
}

.custom-loading-btn:disabled {
    background-color: #6b7280;
    cursor: not-allowed;
}

.custom-loading-btn.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 16px;
    height: 16px;
    margin: -8px 0 0 -8px;
    border: 2px solid transparent;
    border-top: 2px solid #ffffff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

/* フォーム全体のローディング */
.form-container.loading {
    opacity: 0.7;
    pointer-events: none;
}
</style>
