<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=640px, user-scalable=no">
        <title>店舗管理システム</title>
        <style>
            body {
                font-family: ui-sans-serif, system-ui, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0;
                padding: 20px;
            }
            .container {
                background: white;
                border-radius: 12px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                padding: 40px;
                text-align: center;
                width: 600px;
            }
            .title {
                color: #333;
                font-size: 28px;
                font-weight: 600;
                margin-bottom: 8px;
            }
            .subtitle {
                color: #666;
                font-size: 16px;
                margin-bottom: 40px;
            }
            .btn {
                display: block;
                width: 100%;
                padding: 16px 24px;
                margin-bottom: 16px;
                border: none;
                border-radius: 8px;
                font-size: 16px;
                font-weight: 500;
                text-decoration: none;
                transition: all 0.3s ease;
                cursor: pointer;
            }
            .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
            }
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            }
            .btn-secondary {
                background: #f8f9fa;
                color: #495057;
                border: 2px solid #e9ecef;
            }
            .btn-secondary:hover {
                background: #e9ecef;
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1 class="title">店舗管理システム</h1>
            <p class="subtitle">店舗運営をサポートする総合管理システム</p>

            @if (Route::has('login'))
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                        管理画面へ
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        店舗ログイン
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-secondary">
                            店舗新規登録
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </body>
</html>

