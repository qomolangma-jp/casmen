/**
 * 共通ローディング機能
 * すべてのPOST処理時にローディング表示とボタン無効化を行う
 */

class LoadingManager {
    constructor() {
        this.loadingOverlay = null;
        this.originalButtonStates = new Map();
        this.init();
    }

    init() {
        // ローディングオーバーレイを作成
        this.createLoadingOverlay();

        // 全てのフォーム送信をインターセプト
        this.interceptFormSubmissions();

        // 全てのfetch POSTリクエストをインターセプト
        this.interceptFetchRequests();
    }

    createLoadingOverlay() {
        const overlay = document.createElement('div');
        overlay.id = 'global-loading-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(2px);
        `;

        const spinner = document.createElement('div');
        spinner.style.cssText = `
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        `;

        const spinnerIcon = document.createElement('div');
        spinnerIcon.style.cssText = `
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        `;

        const loadingText = document.createElement('div');
        loadingText.textContent = '処理中...';
        loadingText.style.cssText = `
            font-size: 16px;
            color: #333;
            font-weight: 500;
        `;

        // CSSアニメーションを追加
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);

        spinner.appendChild(spinnerIcon);
        spinner.appendChild(loadingText);
        overlay.appendChild(spinner);
        document.body.appendChild(overlay);

        this.loadingOverlay = overlay;
    }

    showLoading(message = '処理中...') {
        // ローディングメッセージを更新
        const loadingText = this.loadingOverlay.querySelector('div:last-child');
        if (loadingText) {
            loadingText.textContent = message;
        }

        // すべてのボタンを無効化
        this.disableAllButtons();

        // ローディングオーバーレイを表示
        this.loadingOverlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    hideLoading() {
        // ローディングオーバーレイを非表示
        this.loadingOverlay.style.display = 'none';
        document.body.style.overflow = '';

        // すべてのボタンを復元
        this.restoreAllButtons();
    }

    disableAllButtons() {
        const buttons = document.querySelectorAll('button, input[type="submit"], input[type="button"]');

        buttons.forEach(button => {
            // 元の状態を保存
            this.originalButtonStates.set(button, {
                disabled: button.disabled,
                textContent: button.textContent || button.value,
                classList: Array.from(button.classList)
            });

            // ボタンを無効化
            button.disabled = true;

            // 送信ボタンの場合はテキストを変更
            if (button.type === 'submit' || button.classList.contains('submit-btn')) {
                if (button.textContent) {
                    button.textContent = '処理中...';
                } else if (button.value) {
                    button.value = '処理中...';
                }
            }

            // 視覚的なフィードバック
            button.style.opacity = '0.6';
            button.style.cursor = 'not-allowed';
        });
    }

    restoreAllButtons() {
        this.originalButtonStates.forEach((originalState, button) => {
            // 元の状態を復元
            button.disabled = originalState.disabled;

            if (button.textContent !== undefined) {
                button.textContent = originalState.textContent;
            } else if (button.value !== undefined) {
                button.value = originalState.textContent;
            }

            // スタイルを復元
            button.style.opacity = '';
            button.style.cursor = '';
        });

        // 状態をクリア
        this.originalButtonStates.clear();
    }

    interceptFormSubmissions() {
        // 既存のフォーム送信をインターセプト
        document.addEventListener('submit', (event) => {
            const form = event.target;

            // GETメソッドの場合はスキップ
            if (form.method && form.method.toLowerCase() === 'get') {
                return;
            }

            // data-no-loading属性がある場合はスキップ
            if (form.hasAttribute('data-no-loading')) {
                return;
            }

            // ローディングを表示
            this.showLoading('送信中...');

            // フォーム送信が完了したらローディングを非表示
            // ページ遷移する場合は自動的に非表示になる
            setTimeout(() => {
                // Ajaxでない通常の送信の場合、ページが変わるのでここは実行されない
                // エラーが発生した場合のためのフォールバック
                this.hideLoading();
            }, 10000); // 10秒後にタイムアウト
        });
    }

    interceptFetchRequests() {
        // 元のfetch関数を保存
        const originalFetch = window.fetch;

        // fetch関数をオーバーライド
        window.fetch = async (url, options = {}) => {
            // POSTリクエストの場合のみ処理
            const method = options.method || 'GET';

            if (method.toUpperCase() === 'POST') {
                // data-no-loading属性のチェック
                const activeElement = document.activeElement;
                if (activeElement && activeElement.closest('[data-no-loading]')) {
                    return originalFetch(url, options);
                }

                // ローディングを表示
                this.showLoading('送信中...');

                try {
                    const response = await originalFetch(url, options);
                    return response;
                } catch (error) {
                    throw error;
                } finally {
                    // レスポンスが返ってきたらローディングを非表示
                    this.hideLoading();
                }
            }

            return originalFetch(url, options);
        };
    }

    // 手動でローディングを制御するためのメソッド
    startLoading(message = '処理中...') {
        this.showLoading(message);
    }

    stopLoading() {
        this.hideLoading();
    }

    // 特定の要素に対してローディングを適用
    applyToElement(element, options = {}) {
        const {
            message = '処理中...',
            disableElement = true
        } = options;

        if (disableElement && element.tagName === 'BUTTON') {
            element.disabled = true;
            element.textContent = message;
        }

        element.addEventListener('click', () => {
            this.showLoading(message);
        });
    }
}

// グローバルインスタンスを作成
window.LoadingManager = new LoadingManager();

// 便利なグローバル関数を提供
window.showLoading = (message) => window.LoadingManager.startLoading(message);
window.hideLoading = () => window.LoadingManager.stopLoading();

// DOMが読み込まれた後に初期化
document.addEventListener('DOMContentLoaded', () => {
    console.log('Loading Manager initialized');
});
