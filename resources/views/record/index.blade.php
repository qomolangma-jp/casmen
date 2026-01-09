@extends('layouts.interview')

@section('title', 'らくらくセルフ面接')

@section('content')
<style>
    .welcome-ribbon {
        position: relative;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        padding: 12px 40px;
        border-radius: 25px;
        color: white;
        font-weight: bold;
        box-shadow: 0 4px 15px rgba(240, 147, 251, 0.4);
        transform: perspective(100px) rotateX(5deg);
    }

    .welcome-ribbon::before,
    .welcome-ribbon::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 0;
        height: 0;
        border-style: solid;
        transform: translateY(-50%);
    }

    .welcome-ribbon::before {
        left: -15px;
        border-width: 20px 15px 20px 0;
        border-color: transparent #f5576c transparent transparent;
    }

    .welcome-ribbon::after {
        right: -15px;
        border-width: 20px 0 20px 15px;
        border-color: transparent transparent transparent #f093fb;
    }

    .point-box {
        position: relative;
        background: linear-gradient(135deg, #e8d5ff 0%, #d8b4fe 100%);
        border: 3px solid white;
        border-radius: 25px;
        padding: 30px;
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.2);
    }

    .point-box::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(135deg, #a855f7, #ec4899);
        border-radius: 25px;
        z-index: -1;
    }

    .character-bear {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #fbb6ce 0%, #f9a8d4 100%);
        border-radius: 50%;
        border: 4px solid #f472b6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        box-shadow: 0 4px 15px rgba(244, 114, 182, 0.3);
    }

    .speech-bubble::after {
        content: '';
        position: absolute;
        bottom: 15px;
        right: -8px;
        width: 0;
        height: 0;
        border: 8px solid transparent;
        border-left-color: #fce7f3;
        border-right: 0;
        margin-top: -8px;
    }

    /* カメラプレビュー用のスタイル */
    .camera-preview-container {
        position: relative;
        width: 100%;
        aspect-ratio: 4/3;
        max-width: 100%;
        margin: 0 auto;
    }

    /* STEPインジケーター用のスタイル */
    .step-indicator {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1000;
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 25px;
        padding: 8px 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border: 2px solid #e5e7eb;
    }

    .step-number {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
        margin-right: 8px;
    }

    .step-current {
        background: linear-gradient(135deg, #a855f7, #ec4899);
        color: white;
        box-shadow: 0 2px 8px rgba(168, 85, 247, 0.3);
    }

    .step-completed {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .step-upcoming {
        background: #f3f4f6;
        color: #9ca3af;
        border: 2px solid #e5e7eb;
    }

    .step-text {
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }

    .step-progress {
        margin-left: 12px;
        font-size: 12px;
        color: #6b7280;
    }

    @media (max-width: 640px) {
        .camera-preview-container {
            aspect-ratio: 16/9; /* スマホでは横長にする */
        }

        .character-bear {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }

        .welcome-ribbon {
            padding: 8px 30px;
            font-size: 1rem;
        }

        .step-indicator {
            top: 10px;
            padding: 6px 16px;
            left: 10px;
            right: 10px;
            transform: none;
            width: calc(100% - 20px);
        }

        .step-number {
            width: 28px;
            height: 28px;
            font-size: 12px;
        }

        .step-text {
            font-size: 12px;
        }

        .step-progress {
            font-size: 10px;
        }
    }
</style>
<div class="min-h-screen py-8" style="background: linear-gradient(180deg, #fdf2f8 0%, #fce7f3 100%);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width: 640px;">
        @if(isset($errorMessage))
            <!-- エラーメッセージ -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <h3 class="text-sm font-medium text-red-800">{{ $errorMessage }}</h3>
                </div>
            </div>
        @elseif($isValidToken)
            <!-- STEPインジケーター：ステップ1 -->
            <div id="stepIndicator1" class="step-indicator">
                <div class="step-number step-current">1</div>
                <div class="step-text">面接の説明</div>
                <div class="step-progress">1/5</div>
            </div>

            <!-- らくらくセルフ面接メインコンテンツ -->
            <div id="welcomeArea" class="text-center">
                <!-- ヘッダーリボン -->
                <div class="relative mb-8">
                    <div class="welcome-ribbon inline-block text-lg">
                        WELCOME TO
                    </div>
                </div>

                <!-- メインタイトル -->
                <div class="mb-6">
                    <h1 class="text-5xl font-bold mb-4" style="color: #f5576c; text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">
                        らくらく<br>セルフ面接
                    </h1>
                    <p class="text-lg text-pink-500 font-medium">
                        スマホからカンタンな質問に<br>答えるだけ！
                    </p>

                    <!-- キャラクター -->
                    <div class="flex justify-center mt-6 mb-8">
                        <div class="character-bear">
                            <div>🐻</div>
                        </div>
                    </div>
                </div>

                <!-- POINTセクション -->
                <div class="point-box mb-8">
                    <div class="mb-6">
                        <h2 class="text-3xl font-bold text-purple-600 mb-4">POINT</h2>
                    </div>

                    <div class="space-y-4 text-left">
                        <div class="flex items-start">
                            <span class="text-pink-400 text-xl mr-3">☆</span>
                            <span class="text-gray-700 font-medium">面接官と合わないから安心</span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-pink-400 text-xl mr-3">★</span>
                            <span class="text-gray-700 font-medium">24時間365日いつでも面接可能</span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-pink-400 text-xl mr-3">☆</span>
                            <span class="text-gray-700 font-medium">所要時間はたったの2分</span>
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        <p class="text-gray-700 leading-relaxed">
                            リラックスして、普段のあなたのままで<br>
                            質問に答えてください。「次へ」をタップする<br>
                            と、やり方の説明に進みます。
                        </p>
                    </div>
                </div>

                <!-- 次へボタン -->
                <div class="mb-8">
                    <button id="startInterviewBtn" class="w-full bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-bold py-4 px-8 rounded-full text-xl transition duration-300 shadow-lg">
                        次へ
                    </button>
                </div>

                <!-- フッター -->
                <div class="text-center text-gray-600 text-sm leading-relaxed">
                    <p class="mb-2">ご不明点やトラブルがあれば、下記のサポートまでお気軽にご連絡ください。</p>
                    <a href="mailto:support@casmen.jp" class="text-blue-500 hover:text-blue-600 font-medium">
                        support@casmen.jp
                    </a>
                </div>
            </div>

            <!-- STEPインジケーター：ステップ2 -->
            <div id="stepIndicator2" class="step-indicator" style="display: none;">
                <div class="step-number step-current">2</div>
                <div class="step-text">やり方の説明</div>
                <div class="step-progress">2/5</div>
            </div>

            <!-- セルフ面接のやり方（初期は非表示） -->
            <div id="howToArea" class="text-center" style="display: none;">
                <!-- メインタイトル -->
                <div class="mb-6">
                    <h1 class="text-4xl font-bold mb-4" style="color: #f5576c; text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">
                        らくらくセルフ面接
                    </h1>
                </div>

                <!-- 説明エリア -->
                <div class="point-box mb-8">
                    <!-- タイトルリボン -->
                    <div class="mb-6">
                        <div class="welcome-ribbon inline-block text-lg mb-4">
                            セルフ面接のやり方
                        </div>
                    </div>

                    <div class="space-y-6 text-left">
                        <!-- カメラ・マイクをON -->
                        <div class="flex items-start">
                            <span class="text-purple-500 text-xl mr-3">☆</span>
                            <div>
                                <h3 class="font-bold text-purple-600 mb-1">カメラ・マイクをON</h3>
                                <p class="text-gray-700 text-sm">
                                    画面に出る許可ポップアップで「OK」を<br>
                                    タップください。
                                </p>
                            </div>
                        </div>

                        <!-- 録画ボタンスタート -->
                        <div class="flex items-start">
                            <span class="text-purple-500 text-xl mr-3">☆</span>
                            <div>
                                <h3 class="font-bold text-purple-600 mb-1">録画ボタンスタート</h3>
                                <p class="text-gray-700 text-sm">
                                    セルフ面接スタートボタンをタップしてか<br>
                                    ら、3秒後に質問が始ります。
                                </p>
                            </div>
                        </div>

                        <!-- 質問は20問・約2分 -->
                        <div class="flex items-start">
                            <span class="text-purple-500 text-xl mr-3">☆</span>
                            <div>
                                <h3 class="font-bold text-purple-600 mb-1">質問は20問・約2分</h3>
                                <p class="text-gray-700 text-sm">
                                    1問につき約5秒。テンポよく表示される質<br>
                                    問に、あなたのペースで答えてください。
                                </p>
                            </div>
                        </div>

                        <!-- やり直しは1回だけOK -->
                        <div class="flex items-start">
                            <span class="text-purple-500 text-xl mr-3">☆</span>
                            <div>
                                <h3 class="font-bold text-purple-600 mb-1">やり直しは1回だけOK</h3>
                                <p class="text-gray-700 text-sm">
                                    「失敗した！」と思ったら、<br>
                                    もう一度だけ録画できます。
                                </p>
                            </div>
                        </div>

                        <!-- 最後に確認して送信 -->
                        <div class="flex items-start">
                            <span class="text-purple-500 text-xl mr-3">☆</span>
                            <div>
                                <h3 class="font-bold text-purple-600 mb-1">最後に確認して送信</h3>
                                <p class="text-gray-700 text-sm">
                                    確認画面で内容を見て<br>
                                    「送信」を押せば完了です。
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- キャラクター -->
                    <div class="flex justify-end mt-6">
                        <div class="character-bear">
                            <div>🐻</div>
                        </div>
                    </div>
                </div>

                <!-- 個人情報の取り扱いについて -->
                <div class="mb-6">
                    <a href="#" class="text-blue-500 hover:text-blue-600 font-medium underline">
                        個人情報の取り扱いについて
                    </a>
                </div>

                <!-- 個人情報に同意して次へボタン -->
                <div class="mb-8">
                    <button id="agreeAndNextBtn" class="w-full bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-bold py-4 px-8 rounded-full text-xl transition duration-300 shadow-lg">
                        個人情報に同意して次へ
                    </button>
                </div>

                <!-- フッター -->
                <div class="text-center text-gray-600 text-sm leading-relaxed">
                    <p class="mb-2">ご不明点やトラブルがあれば、下記のサポートまでお気軽にご連絡ください。</p>

                    <a href="mailto:support@casmen.jp" class="text-blue-500 hover:text-blue-600 font-medium">
                        support@casmen.jp
                    </a>
                </div>
            </div>

            <!-- STEPインジケーター：ステップ3 -->
            <div id="stepIndicator3" class="step-indicator" style="display: none;">
                <div class="step-number step-current">3</div>
                <div class="step-text">カメラ確認・準備</div>
                <div class="step-progress">3/5</div>
            </div>

            <!-- 準備完了画面（初期は非表示） -->
            <div id="readyArea" class="text-center" style="display: none;">
                <!-- メインタイトル -->
                <div class="mb-6">
                    <h1 class="text-4xl font-bold mb-4" style="color: #f5576c; text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">
                        らくらくセルフ面接
                    </h1>
                </div>

                <!-- 準備完了メッセージ -->
                <div class="point-box mb-8">
                    <!-- タイトルリボン -->
                    <div class="mb-6">
                        <div class="welcome-ribbon inline-block text-lg mb-4">
                            準備ができましたら
                        </div>
                    </div>

                    <div class="text-center mb-6">
                        <p class="text-gray-700 text-lg leading-relaxed mb-4">
                            <strong>【セルフ面接スタート】</strong>ボタン<br>
                            をタップしてください。
                        </p>
                        <p class="text-gray-700 text-lg leading-relaxed">
                            3秒後に質問がスタートします。
                        </p>
                    </div>

                    <!-- カメラプレビューエリア -->
                    <div class="mt-8 mb-6">
                        <!-- カメラプレビュー（実際の映像） -->
                        <div class="camera-preview-container bg-gray-900 rounded-xl border-4 border-purple-300 overflow-hidden shadow-lg">
                            <video id="readyVideoPreview" class="w-full h-full object-cover" muted autoplay playsinline></video>
                            <!-- カメラが利用できない場合のフォールバック -->
                            <div id="cameraFallback" class="w-full h-full flex items-center justify-center text-center" style="display: none;">
                                <div>
                                    <div class="w-20 h-20 bg-pink-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                                        <span class="text-4xl">�</span>
                                    </div>
                                    <p class="text-white text-lg font-medium">カメラを準備中...</p>
                                    <p class="text-gray-300 text-sm mt-2">カメラアクセスを許可してください</p>
                                </div>
                            </div>
                        </div>

                        <!-- 身だしなみチェックのメッセージ -->
                        <div class="mt-4 text-center">
                            <p class="text-purple-600 font-medium text-lg mb-2">📹 身だしなみをチェックしてください</p>
                            <p class="text-gray-600 text-sm">画面に映る自分の姿を確認して、準備が整ったらスタートボタンを押してください</p>
                        </div>

                        <!-- キャラクターと吹き出し -->
                        <div class="flex justify-end items-center mt-6">
                            <!-- 吹き出し -->
                            <div class="bg-pink-100 border-2 border-pink-300 rounded-2xl p-3 mr-3 relative">
                                <p class="text-pink-600 font-bold text-base">
                                    きれいに<br>映ってる？
                                </p>
                                <!-- 吹き出しの尖り -->
                                <div class="absolute bottom-3 right-[-8px] w-0 h-0 border-l-8 border-l-pink-100 border-t-6 border-t-transparent border-b-6 border-b-transparent"></div>
                            </div>

                            <!-- キャラクター -->
                            <div class="character-bear">
                                <div>🐻</div>
                            </div>
                        </div>
                    </div>

                    <!-- プログレス表示 -->
                    <div class="flex justify-between items-center mt-8 px-4">
                        <div class="flex items-center text-pink-500">
                            <span class="w-6 h-6 bg-pink-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-2">1</span>
                            <span class="text-sm">～～～～～～～～～～～～～～～</span>
                        </div>
                        <div class="flex items-center text-purple-300">
                            <span class="text-sm">～～～～～～～～～～～～～～～</span>
                            <span class="w-6 h-6 bg-purple-300 text-white rounded-full flex items-center justify-center text-sm font-bold ml-2">24</span>
                        </div>
                    </div>
                </div>

                <!-- セルフ面接スタートボタン -->
                <div class="mb-8">
                    <button id="startRecordingNowBtn" class="w-full bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-bold py-4 px-8 rounded-full text-xl transition duration-300 shadow-lg">
                        セルフ面接スタート
                    </button>
                </div>

                <!-- フッター -->
                <div class="text-center text-gray-600 text-sm leading-relaxed">
                    <p class="mb-2">ご不明点やトラブルがあれば、下記のサポートまでお気軽にご連絡ください。</p>

                    <a href="mailto:support@casmen.jp" class="text-blue-500 hover:text-blue-600 font-medium">
                        support@casmen.jp
                    </a>
                </div>
            </div>

            <!-- STEPインジケーター：ステップ4 -->
            <div id="stepIndicator4" class="step-indicator" style="display: none;">
                <div class="step-number step-current">4</div>
                <div class="step-text">面接開始準備</div>
                <div class="step-progress">4/5</div>
            </div>

            <!-- 3秒カウントダウン画面（初期は非表示） -->
            <div id="countdownArea" class="text-center" style="display: none;">
                <!-- メインタイトル -->
                <div class="mb-6">
                    <h1 class="text-4xl font-bold mb-4" style="color: #f5576c; text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">
                        らくらくセルフ面接
                    </h1>
                </div>

                <!-- カウントダウンエリア -->
                <div class="point-box mb-8">
                    <!-- タイトルリボン -->
                    <div class="mb-6">
                        <div class="welcome-ribbon inline-block text-lg mb-4">
                            3秒後に質問がスタートします
                        </div>
                    </div>

                    <!-- 大きなカウントダウン数字 -->
                    <div class="flex justify-center items-center mb-8">
                        <div class="w-64 h-64 bg-pink-200 rounded-full flex items-center justify-center border-8 border-pink-300">
                            <div id="countdownNumber" class="text-8xl font-bold text-pink-600">
                                3
                            </div>
                        </div>
                    </div>

                    <!-- カメラプレビューエリアとキャラクター -->
                    <div class="flex items-end justify-between mt-8">
                        <!-- カメラプレビュー（録画中表示） -->
                        <div class="relative">
                            <div class="w-32 h-40 bg-gray-800 rounded-lg border-4 border-red-500 flex items-center justify-center">
                                <video id="countdownVideoPreview" width="120" height="150" class="rounded-lg" muted autoplay style="object-fit: cover;"></video>
                                <!-- REC表示 -->
                                <div class="absolute top-2 left-2 bg-red-600 text-white text-xs px-2 py-1 rounded flex items-center">
                                    <div class="w-2 h-2 bg-white rounded-full animate-pulse mr-1"></div>
                                    REC
                                </div>
                            </div>
                        </div>

                        <!-- 吹き出しとキャラクター -->
                        <div class="flex-1 flex justify-end items-end">
                            <!-- 吹き出し -->
                            <div class="bg-pink-100 border-2 border-pink-300 rounded-2xl p-4 mr-4 relative">
                                <p class="text-pink-600 font-bold text-lg">
                                    もうすぐ<br>始まるよ！
                                </p>
                                <!-- 吹き出しの尖り -->
                                <div class="absolute bottom-4 right-[-10px] w-0 h-0 border-l-10 border-l-pink-100 border-t-8 border-t-transparent border-b-8 border-b-transparent"></div>
                            </div>

                            <!-- キャラクター -->
                            <div class="character-bear">
                                <div>🐻</div>
                            </div>
                        </div>
                    </div>

                    <!-- プログレス表示 -->
                    <div class="flex justify-between items-center mt-8 px-4">
                        <div class="flex items-center text-pink-500">
                            <span class="w-6 h-6 bg-pink-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-2">1</span>
                            <span class="text-sm">～～～～～～～～～～～～～～～</span>
                        </div>
                        <div class="flex items-center text-purple-300">
                            <span class="text-sm">～～～～～～～～～～～～～～～</span>
                            <span class="w-6 h-6 bg-purple-300 text-white rounded-full flex items-center justify-center text-sm font-bold ml-2">24</span>
                        </div>
                    </div>
                </div>

                <!-- 最初からやり直すボタン -->
                <div class="mb-8">
                    <button id="restartBtn" class="w-full bg-gray-400 hover:bg-gray-500 text-white font-bold py-4 px-8 rounded-full text-xl transition duration-300 shadow-lg">
                        最初からやり直す
                    </button>
                </div>

                <!-- フッター -->
                <div class="text-center text-gray-600 text-sm leading-relaxed">
                    <p class="mb-2">ご不明点やトラブルがあれば、下記のサポートまでお気軽にご連絡ください。</p>
                    <a href="mailto:support@casmen.jp" class="text-blue-500 hover:text-blue-600 font-medium">
                        support@casmen.jp
                    </a>
                </div>
            </div>

            <!-- プログレスステップ -->
            <div id="progressSteps" class="mb-8" style="display: none;">
                <div class="flex justify-center items-center space-x-4">
                    <div id="step1" class="flex items-center">
                        <div class="w-8 h-8 bg-pink-500 text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                        <span class="ml-2 text-sm text-pink-600 font-medium">質問確認</span>
                    </div>
                    <div class="w-8 h-0.5 bg-gray-300"></div>
                    <div id="step2" class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold">2</div>
                        <span class="ml-2 text-sm text-gray-600">録画</span>
                    </div>
                    <div class="w-8 h-0.5 bg-gray-300"></div>
                    <div id="step3" class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold">3</div>
                        <span class="ml-2 text-sm text-gray-600">完了</span>
                    </div>
                </div>
            </div>

            <!-- STEPインジケーター：ステップ5 -->
            <div id="stepIndicator5" class="step-indicator" style="display: none;">
                <div class="step-number step-current">5</div>
                <div class="step-text">面接実施中</div>
                <div class="step-progress">5/5</div>
            </div>

            <!-- 面接質問エリア（初期は非表示） -->
            <div id="interviewArea" class="bg-white rounded-2xl shadow-lg p-8 mb-6" style="display: none;">
                <!-- 質問表示エリア（自動進行版） -->
                <div id="questionStep" class="text-center">
                    <!-- 質問番号とタイトル -->
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-pink-100 rounded-full mb-4">
                            <span class="text-2xl font-bold text-pink-600" id="questionNumber">1</span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 mb-4" id="questionText">
                            質問を読み込み中...
                        </h2>
                    </div>

                    <!-- カメラプレビューエリア -->
                    <div class="mb-6 relative">
                        <video id="interviewVideoPreview" width="400" height="300" class="mx-auto rounded-lg shadow-md bg-black" muted autoplay></video>

                        <!-- 秒読みオーバーレイ -->
                        <div id="countdownOverlay" class="absolute inset-0 bg-black bg-opacity-70 rounded-lg flex items-center justify-center" style="display: none; top: 0; left: 0; width: 400px; height: 300px;">
                            <div class="text-white text-center">
                                <div id="countdownCircle" class="w-28 h-28 border-4 border-white rounded-full flex items-center justify-center mx-auto mb-3">
                                    <span id="countdownNumber" class="text-5xl font-bold"></span>
                                </div>
                                <p class="text-xl font-medium">次の質問まで</p>
                            </div>
                        </div>
                    </div>

                    <!-- 録画中表示と残り時間 -->
                    <div class="mb-6">
                        <div id="recordingIndicator" class="flex justify-center items-center text-red-600 mb-4" style="display: flex;">
                            <div class="w-4 h-4 bg-red-600 rounded-full animate-pulse mr-2"></div>
                            <span class="text-lg font-bold">REC</span>
                        </div>

                        <!-- 5秒カウントダウンタイマー -->
                        <div class="flex justify-center items-center space-x-4">
                            <div class="text-2xl font-bold text-pink-600">
                                残り時間: <span id="questionTimer">5</span>秒
                            </div>
                        </div>
                    </div>

                    <!-- 進行状況表示 -->
                    <div class="mb-6">
                        <div class="text-sm text-gray-600">
                            質問 <span id="currentQuestionNum">1</span> / <span id="totalQuestions">{{ count($questions) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div id="questionProgress" class="bg-pink-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>
                </div>

                <!-- 最初からやり直すボタン -->
                <div class="text-center mb-4">
                    <button id="interviewRestartBtn" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-3 px-6 rounded-full text-lg transition duration-300">
                        最初からやり直す
                    </button>
                </div>

                <!-- 録画エリア -->
                <div id="recordingStep" class="text-center" style="display: none;">
                    <div class="mb-6">
                        <video id="videoPreview" width="400" height="300" class="mx-auto rounded-lg shadow-md bg-black" muted autoplay></video>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2" id="currentQuestion">
                            質問5: 最近ハマっていることは？
                        </h3>
                        <div class="flex justify-center items-center space-x-4">
                            <div id="timer" class="text-2xl font-bold text-pink-600">01:00</div>
                            <div id="recordingIndicator" class="flex items-center text-red-600" style="display: none;">
                                <div class="w-3 h-3 bg-red-600 rounded-full animate-pulse mr-2"></div>
                                <span class="text-sm font-medium">録画中</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <button id="startRecord" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-8 rounded-full text-lg transition duration-200">
                                録画開始
                            </button>
                            <button id="stopRecord" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-8 rounded-full text-lg transition duration-200 ml-4" disabled>
                                録画停止
                            </button>
                        </div>

                        <div>
                            <button id="retryRecord" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded-full transition duration-200" style="display: none;">
                                もう一度録画
                            </button>
                            <button id="nextQuestion" class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-6 rounded-full transition duration-200 ml-4" style="display: none;">
                                次の質問へ
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 完了エリア -->
                <div id="completionStep" class="text-center" style="display: none;">
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                            <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-800 mb-4">お疲れさまでした！</h2>
                    <p class="text-gray-600 mb-8">
                        面接動画の録画が完了しました。<br>
                        内容を確認してアップロードしてください。
                    </p>

                    <div class="space-y-4">
                        <button id="previewVideo" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-full transition duration-200">
                            録画内容を確認
                        </button>
                        <br>
                        <button id="uploadVideo" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-full transition duration-200">
                            面接動画をアップロード
                        </button>
                    </div>

                    <div id="uploadProgress" class="mt-4 text-center text-sm text-gray-600" style="display: none;">
                        <div class="bg-gray-200 rounded-full h-2 mb-2">
                            <div id="progressBar" class="bg-pink-500 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                        アップロード中...
                    </div>
                </div>
            </div>

            <!-- 質問完了エリア（新規追加） -->
            <!-- STEPインジケーター：完了 -->
            <div id="stepIndicatorComplete" class="step-indicator" style="display: none;">
                <div class="step-number step-completed">✓</div>
                <div class="step-text">面接完了</div>
                <div class="step-progress">完了</div>
            </div>

            <div id="interviewCompleteArea" class="bg-white rounded-2xl shadow-lg p-8 mb-6" style="display: none;">
                <div class="text-center">
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                            <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-800 mb-4">これで質問はすべて完了です。</h2>
                    <p class="text-gray-600 mb-2">
                        問題がなければ「送信する」をタップしてください。
                    </p>
                    <p class="text-lg font-medium text-pink-600 mb-8">
                        セルフ面談おつかれさまでした。
                    </p>

                    <!-- ボタンエリア -->
                    <div class="space-y-4 mb-6">
                        <!-- 録り直しボタン -->
                        <button id="retakeBtn" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-full transition duration-200">
                            録り直し（残り<span id="retakeCount">1</span>回）
                        </button>

                        <!-- プレビューボタン -->
                        <button id="previewAllBtn" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-full transition duration-200">
                            プレビュー ▶
                        </button>

                        <!-- 送信するボタン -->
                        <button id="submitAllBtn" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-full transition duration-200">
                            送信する
                        </button>
                    </div>

                    <!-- 注意書き -->
                    <div class="text-sm text-gray-500">
                        <p>※録り直しは1度のみ可能です</p>
                        <p>※送信後は内容の変更はできません</p>
                    </div>
                </div>
            </div>
        @else
            <!-- 無効なトークンの場合 -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <h3 class="text-sm font-medium text-yellow-800">面接URLが無効か期限切れです</h3>
                </div>
                <p class="mt-2 text-sm text-yellow-700">
                    有効な面接URLを確認してください。URLの有効期限が切れている可能性があります。
                </p>
            </div>
        @endif
    </div>
</div>

@if($isValidToken)
<script>
let mediaRecorder;
let recordedChunks = [];
let stream;
let timer;
let timeLeft = 60; // 60秒

// サーバーから渡された質問データ
const questions = @json($questions);
let currentQuestionIndex = 0;

// ステップ管理
function updateStepIndicator(step, questionNumber = null) {
    // 全てのSTEPインジケーターを隠す
    for (let i = 1; i <= 5; i++) {
        const indicator = document.getElementById(`stepIndicator${i}`);
        if (indicator) {
            indicator.style.display = 'none';
        }
    }

    // 現在のステップのインジケーターを表示
    const currentIndicator = document.getElementById(`stepIndicator${step}`);
    if (currentIndicator) {
        currentIndicator.style.display = 'flex';

        // 面接中の場合は質問番号を表示
        if (step === 5 && questionNumber) {
            const progressText = currentIndicator.querySelector('.step-progress');
            if (progressText) {
                progressText.textContent = `質問 ${questionNumber}/${questions.length}`;
            }
        }
    }
}// 従来のupdateStep関数（既存の機能を維持）
function updateStep(step) {
    currentStep = step;

    // プログレスバーの更新
    for (let i = 1; i <= 3; i++) {
        const stepElement = document.getElementById(`step${i}`);
        if (stepElement) {
            const circle = stepElement.querySelector('div');
            const text = stepElement.querySelector('span');

            if (i < step) {
                // 完了済み
                circle.className = 'w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold';
                text.className = 'ml-2 text-sm text-green-600 font-medium';
            } else if (i === step) {
                // 現在のステップ
                circle.className = 'w-8 h-8 bg-pink-500 text-white rounded-full flex items-center justify-center text-sm font-bold';
                text.className = 'ml-2 text-sm text-pink-600 font-medium';
            } else {
                // 未完了
                circle.className = 'w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold';
                text.className = 'ml-2 text-sm text-gray-600';
            }
        }
    }

    // ステップ表示の切り替え
    document.getElementById('questionStep').style.display = step === 1 ? 'block' : 'none';
    document.getElementById('recordingStep').style.display = step === 2 ? 'block' : 'none';
    document.getElementById('completionStep').style.display = step === 3 ? 'block' : 'none';
}

// タイマー機能
function startTimer() {
    timeLeft = 60;
    updateTimerDisplay();

    timer = setInterval(() => {
        timeLeft--;
        updateTimerDisplay();

        if (timeLeft <= 0) {
            clearInterval(timer);
            stopRecording();
        }
    }, 1000);
}

function updateTimerDisplay() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    document.getElementById('timer').textContent =
        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

// カメラアクセス
async function initCamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: {
                width: { ideal: 1280 },
                height: { ideal: 720 },
                facingMode: 'user' // フロントカメラを優先
            },
            audio: true
        });

        // 準備画面のビデオプレビューに映像を設定
        const readyVideoPreview = document.getElementById('readyVideoPreview');
        const cameraFallback = document.getElementById('cameraFallback');

        if (readyVideoPreview) {
            readyVideoPreview.srcObject = stream;
            readyVideoPreview.style.display = 'block';
            if (cameraFallback) {
                cameraFallback.style.display = 'none';
            }
        }

        // 他のビデオプレビューも設定（既存の処理を維持）
        const videoPreview = document.getElementById('videoPreview');
        if (videoPreview) {
            videoPreview.srcObject = stream;
        }
    } catch (err) {
        console.error('カメラアクセスエラー:', err);

        // エラー時はフォールバック表示
        const readyVideoPreview = document.getElementById('readyVideoPreview');
        const cameraFallback = document.getElementById('cameraFallback');

        if (readyVideoPreview && cameraFallback) {
            readyVideoPreview.style.display = 'none';
            cameraFallback.style.display = 'flex';
        }

        // ユーザーにカメラアクセス許可を促す
        alert('カメラとマイクへのアクセスを許可してください。\nブラウザの設定でカメラとマイクの許可を確認してください。');
    }
}

// 最初の「次へ」ボタンのイベントリスナー
document.getElementById('startInterviewBtn').addEventListener('click', () => {
    // ウェルカムメッセージエリアを隠す
    const welcomeArea = document.getElementById('welcomeArea');
    if (welcomeArea) {
        welcomeArea.style.display = 'none';
    }

    // セルフ面接のやり方エリアを表示
    const howToArea = document.getElementById('howToArea');
    if (howToArea) {
        howToArea.style.display = 'block';
    }

    // STEPインジケーターを更新（ステップ2へ）
    updateStepIndicator(2);
});

// 「個人情報に同意して次へ」ボタンのイベントリスナー
document.getElementById('agreeAndNextBtn').addEventListener('click', () => {
    // セルフ面接のやり方エリアを隠す
    const howToArea = document.getElementById('howToArea');
    if (howToArea) {
        howToArea.style.display = 'none';
    }

    // 準備完了エリアを表示
    const readyArea = document.getElementById('readyArea');
    if (readyArea) {
        readyArea.style.display = 'block';
    }

    // STEPインジケーターを更新（ステップ3へ）
    updateStepIndicator(3);

    // カメラを初期化（プレビュー用）
    initCamera();
});

// 「セルフ面接スタート」ボタンのイベントリスナー
document.getElementById('startRecordingNowBtn').addEventListener('click', () => {
    // 準備完了エリアを隠す
    const readyArea = document.getElementById('readyArea');
    if (readyArea) {
        readyArea.style.display = 'none';
    }

    // カウントダウンエリアを表示
    const countdownArea = document.getElementById('countdownArea');
    if (countdownArea) {
        countdownArea.style.display = 'block';
    }

    // STEPインジケーターを更新（ステップ4へ）
    updateStepIndicator(4);

    // カウントダウンビデオプレビューを設定
    const countdownVideo = document.getElementById('countdownVideoPreview');
    if (countdownVideo && stream) {
        countdownVideo.srcObject = stream;
    }

    // 3秒カウントダウンを開始
    startCountdown();
});

// 3秒カウントダウン機能
function startCountdown() {
    let count = 3;
    const countdownNumber = document.getElementById('countdownNumber');

    const countdownInterval = setInterval(() => {
        if (countdownNumber) {
            countdownNumber.textContent = count;
        }

        count--;

        if (count < 0) {
            clearInterval(countdownInterval);
            // カウントダウン完了後、面接開始
            startInterview();
        }
    }, 1000);
}

// 面接開始処理
function startInterview() {
    // カウントダウンエリアを隠す
    const countdownArea = document.getElementById('countdownArea');
    if (countdownArea) {
        countdownArea.style.display = 'none';
    }

    // STEPインジケーターを更新（ステップ5へ）
    updateStepIndicator(5);

    // プログレスステップを表示
    const progressSteps = document.getElementById('progressSteps');
    if (progressSteps) {
        progressSteps.style.display = 'block';
    }

    // 面接エリアを表示
    const interviewArea = document.getElementById('interviewArea');
    if (interviewArea) {
        interviewArea.style.display = 'block';
    }

    // 最初の質問を表示し、自動録画開始
    showQuestionAndStartRecording(0);
}

// 「最初からやり直す」ボタンのイベントリスナー（カウントダウンエリア）
document.getElementById('restartBtn').addEventListener('click', () => {
    // カウントダウンエリアを隠す
    const countdownArea = document.getElementById('countdownArea');
    if (countdownArea) {
        countdownArea.style.display = 'none';
    }

    // セルフ面接のやり方エリアを表示
    const howToArea = document.getElementById('howToArea');
    if (howToArea) {
        howToArea.style.display = 'block';
    }

    // カウントダウン数字をリセット
    const countdownNumber = document.getElementById('countdownNumber');
    if (countdownNumber) {
        countdownNumber.textContent = '3';
    }
});

// 「最初からやり直す」ボタンのイベントリスナー（面接エリア）
document.getElementById('interviewRestartBtn').addEventListener('click', () => {
    // 録画を停止
    if (mediaRecorder && mediaRecorder.state === 'recording') {
        mediaRecorder.stop();
    }

    // 面接エリアとプログレスステップを隠す
    const interviewArea = document.getElementById('interviewArea');
    const progressSteps = document.getElementById('progressSteps');
    if (interviewArea) {
        interviewArea.style.display = 'none';
    }
    if (progressSteps) {
        progressSteps.style.display = 'none';
    }

    // セルフ面接のやり方エリアを表示
    const howToArea = document.getElementById('howToArea');
    if (howToArea) {
        howToArea.style.display = 'block';
    }

    // 変数をリセット
    currentQuestionIndex = 0;
    recordedChunks = [];

    // タイマーをリセット
    const questionTimer = document.getElementById('questionTimer');
    if (questionTimer) {
        questionTimer.textContent = '5';
    }

    // 進行状況バーをリセット
    const questionProgress = document.getElementById('questionProgress');
    if (questionProgress) {
        questionProgress.style.width = '0%';
    }
});

// 質問表示関数
function showQuestion(index) {
    if (index >= questions.length) {
        // 全質問完了
        completeInterview();
        return;
    }

    currentQuestionIndex = index;
    const question = questions[index];

    // STEPインジケーターを更新（質問番号付き）
    updateStepIndicator(5, index + 1);

    const questionNumber = document.getElementById('questionNumber');
    const questionText = document.getElementById('questionText');
    const startRecordingBtn = document.getElementById('startRecordingBtn');

    if (questionNumber) {
        questionNumber.textContent = index + 1;
    }
    if (questionText) {
        questionText.textContent = question.q;
    }
    if (startRecordingBtn) {
        startRecordingBtn.style.display = 'block';
    }
}

// 質問表示と自動録画開始（5秒制限）
function showQuestionAndStartRecording(index) {
    console.log(`質問${index + 1}を開始します`);

    if (!questions || questions.length === 0) {
        console.error('質問データが読み込まれていません');
        alert('質問データが読み込まれていません。ページを再読み込みしてください。');
        return;
    }

    if (index >= questions.length) {
        // 全質問完了
        completeInterview();
        return;
    }

    currentQuestionIndex = index;
    const question = questions[index];

    // 質問情報を更新
    const questionNumber = document.getElementById('questionNumber');
    const questionText = document.getElementById('questionText');
    const currentQuestionNum = document.getElementById('currentQuestionNum');
    const questionTimer = document.getElementById('questionTimer');
    const questionProgress = document.getElementById('questionProgress');

    if (questionNumber) {
        questionNumber.textContent = index + 1;
    }
    if (questionText) {
        questionText.textContent = question.q;
    }
    if (currentQuestionNum) {
        currentQuestionNum.textContent = index + 1;
    }

    // 進行状況バーを更新
    if (questionProgress) {
        const progressPercent = ((index + 1) / questions.length) * 100;
        questionProgress.style.width = progressPercent + '%';
    }

    // カメラプレビューを設定
    const interviewVideo = document.getElementById('interviewVideoPreview');
    if (interviewVideo && stream) {
        interviewVideo.srcObject = stream;
    }

    // 録画開始
    startQuestionRecording();

    // 5秒カウントダウンタイマー
    let timeLeft = 5;
    const timerInterval = setInterval(() => {
        if (questionTimer) {
            questionTimer.textContent = timeLeft;
        }

        timeLeft--;

        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            stopQuestionRecording();

            // 次の質問へ（2秒の秒読みオーバーレイ表示）
            const nextIndex = index + 1;
            if (nextIndex < questions.length) {
                // 秒読みオーバーレイを表示
                showCountdownOverlay(() => {
                    showQuestionAndStartRecording(nextIndex);
                });
            } else {
                // 面接完了前の秒読み
                showCountdownOverlay(() => {
                    completeInterview();
                });
            }
        }
    }, 1000);
}

// 録画開始
function startRecording() {
    recordedChunks = [];

    try {
        // サポートされているメディアタイプを取得
        const mimeType = getSupportedMimeType();
        const options = mimeType ? { mimeType: mimeType } : {};

        mediaRecorder = new MediaRecorder(stream, options);

        mediaRecorder.ondataavailable = (event) => {
            if (event.data.size > 0) {
                recordedChunks.push(event.data);
            }
        };

        mediaRecorder.onstop = () => {
            clearInterval(timer);
            document.getElementById('recordingIndicator').style.display = 'none';
            document.getElementById('startRecord').disabled = false;
            document.getElementById('stopRecord').disabled = true;
            document.getElementById('retryRecord').style.display = 'inline-block';
            document.getElementById('nextQuestion').style.display = 'inline-block';
        };

        mediaRecorder.start();
        startTimer();

        document.getElementById('startRecord').disabled = true;
        document.getElementById('stopRecord').disabled = false;
        document.getElementById('recordingIndicator').style.display = 'flex';

        console.log('録画開始:', mimeType || 'デフォルト');
    } catch (error) {
        console.error('録画開始エラー:', error);
        alert('録画の開始に失敗しました。ブラウザがサポートしていない可能性があります。');
    }
}

// サポートされているメディアタイプを取得
function getSupportedMimeType() {
    const types = [
        'video/webm;codecs=vp9,opus',
        'video/webm;codecs=vp8,opus',
        'video/webm;codecs=h264,opus',
        'video/webm',
        'video/mp4;codecs=h264,aac',
        'video/mp4'
    ];

    for (let type of types) {
        if (MediaRecorder.isTypeSupported(type)) {
            console.log('サポートされているメディアタイプ:', type);
            return type;
        }
    }

    console.warn('サポートされているメディアタイプが見つかりません。デフォルトを使用します。');
    return null;
}

// 質問用録画開始（5秒制限）
function startQuestionRecording() {
    // カメラストリームが利用可能かチェック
    if (!stream) {
        console.error('カメラストリームが初期化されていません');
        return;
    }

    recordedChunks = [];

    try {
        // サポートされているメディアタイプを取得
        const mimeType = getSupportedMimeType();
        const options = mimeType ? { mimeType: mimeType } : {};

        mediaRecorder = new MediaRecorder(stream, options);

        mediaRecorder.ondataavailable = (event) => {
            if (event.data.size > 0) {
                recordedChunks.push(event.data);
            }
        };

        mediaRecorder.onstop = () => {
            // 録画完了後、動画ファイルを保存
            saveQuestionVideo();
        };

        mediaRecorder.start();
        console.log(`質問${currentQuestionIndex + 1}の録画を開始しました (${mimeType || 'デフォルト'})`);

        // 録画中表示
        const recordingIndicator = document.getElementById('recordingIndicator');
        if (recordingIndicator) {
            recordingIndicator.style.display = 'flex';
        }
    } catch (error) {
        console.error('録画開始エラー:', error);

        // エラーメッセージを表示
        alert('録画の開始に失敗しました。ブラウザがサポートしていない可能性があります。\n\nChrome、Firefox、Edgeの最新版をお試しください。');
    }
}

// 質問用録画停止
function stopQuestionRecording() {
    if (mediaRecorder && mediaRecorder.state === 'recording') {
        mediaRecorder.stop();
        console.log('録画停止完了');
    }

    // MediaRecorderをリセット
    mediaRecorder = null;

    // 録画中表示を隠す
    const recordingIndicator = document.getElementById('recordingIndicator');
    if (recordingIndicator) {
        recordingIndicator.style.display = 'none';
    }
}

// 質問動画保存
function saveQuestionVideo() {
    if (!recordedChunks || recordedChunks.length === 0) {
        console.warn('録画データがありません');
        return;
    }

    // 使用されたメディアタイプを取得
    const mimeType = getSupportedMimeType() || 'video/webm';
    const blob = new Blob(recordedChunks, { type: mimeType });
    const questionNumber = currentQuestionIndex + 1;

    // ファイル拡張子を決定
    const extension = mimeType.includes('mp4') ? 'mp4' : 'webm';

    // 質問ごとの動画ファイルとして保存
    const formData = new FormData();
    formData.append('video', blob, `interview_question_${questionNumber}.${extension}`);
    formData.append('question_number', questionNumber);
    formData.append('total_questions', questions.length);
    formData.append('token', '{{ $token }}');
    formData.append('_token', '{{ csrf_token() }}');

    // バックグラウンドでアップロード
    fetch('{{ route("record.upload") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log(`質問${questionNumber}の動画が保存されました:`, data.file_path);
            console.log('アップロード成功レスポンス:', data);
        } else {
            console.error(`質問${questionNumber}の動画保存失敗:`, data.message);
            alert(`質問${questionNumber}の動画保存に失敗しました: ` + data.message);
        }
    })
    .catch(error => {
        console.error(`質問${questionNumber}の動画保存エラー:`, error);
        alert(`質問${questionNumber}の動画保存でエラーが発生しました`);
    });
}

// 録画停止
function stopRecording() {
    if (mediaRecorder && mediaRecorder.state === 'recording') {
        mediaRecorder.stop();
    }
}

// 録画開始ボタン
const startRecordingBtn = document.getElementById('startRecordingBtn');
if (startRecordingBtn) {
    startRecordingBtn.addEventListener('click', () => {
        const questionStep = document.getElementById('questionStep');
        const recordingStep = document.getElementById('recordingStep');

        if (questionStep) {
            questionStep.style.display = 'none';
        }
        if (recordingStep) {
            recordingStep.style.display = 'block';
        }
        startRecording();
    });
}

// 録画停止ボタン
const stopRecordBtn = document.getElementById('stopRecord');
if (stopRecordBtn) {
    stopRecordBtn.addEventListener('click', stopRecording);
}

// もう一度録画
document.getElementById('retryRecord').addEventListener('click', () => {
    document.getElementById('retryRecord').style.display = 'none';
    document.getElementById('nextQuestion').style.display = 'none';
    timeLeft = 60;
    updateTimerDisplay();
});

// 次の質問へ / 完了へ
document.getElementById('nextQuestion').addEventListener('click', () => {
    updateStep(3);
});

// 動画プレビュー
document.getElementById('previewVideo').addEventListener('click', () => {
    const blob = new Blob(recordedChunks, { type: 'video/webm' });
    const url = URL.createObjectURL(blob);

    // モーダルまたは新しいウィンドウでプレビュー表示
    const previewWindow = window.open('', '_blank');
    previewWindow.document.write(`
        <html>
            <head><title>録画プレビュー</title></head>
            <body style="margin:0; display:flex; justify-content:center; align-items:center; min-height:100vh; background:#000;">
                <video controls autoplay style="max-width:100%; max-height:100%;">
                    <source src="${url}" type="video/webm">
                </video>
            </body>
        </html>
    `);
});

// 動画アップロード
document.getElementById('uploadVideo').addEventListener('click', () => {
    const blob = new Blob(recordedChunks, { type: 'video/webm' });
    uploadVideo(blob);
});

function uploadVideo(blob) {
    const formData = new FormData();
    formData.append('video', blob, 'interview_video.webm');
    formData.append('token', '{{ $token }}');
    formData.append('_token', '{{ csrf_token() }}');

    document.getElementById('uploadProgress').style.display = 'block';
    document.getElementById('uploadVideo').disabled = true;

    // プログレスバーのアニメーション
    let progress = 0;
    const progressInterval = setInterval(() => {
        progress += Math.random() * 10;
        if (progress > 90) progress = 90;
        document.getElementById('progressBar').style.width = progress + '%';
    }, 100);

    fetch('{{ route("record.upload") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        clearInterval(progressInterval);
        document.getElementById('progressBar').style.width = '100%';

        setTimeout(() => {
            if (data.success) {
                alert('面接動画がアップロードされました！');
                window.location.href = '{{ route("record.complete") }}';
            } else {
                alert('アップロードに失敗しました: ' + data.message);
            }
        }, 500);
    })
    .catch(error => {
        clearInterval(progressInterval);
        console.error('アップロードエラー:', error);
        alert('アップロードに失敗しました。');
    })
    .finally(() => {
        document.getElementById('uploadProgress').style.display = 'none';
        document.getElementById('uploadVideo').disabled = false;
    });
}

// 面接完了処理
function completeInterview() {
    // 面接エリアとプログレスステップを隠す
    const interviewArea = document.getElementById('interviewArea');
    const progressSteps = document.getElementById('progressSteps');
    if (interviewArea) {
        interviewArea.style.display = 'none';
    }
    if (progressSteps) {
        progressSteps.style.display = 'none';
    }

    // 全てのSTEPインジケーターを隠す
    for (let i = 1; i <= 5; i++) {
        const indicator = document.getElementById(`stepIndicator${i}`);
        if (indicator) {
            indicator.style.display = 'none';
        }
    }

    // 完了用STEPインジケーターを表示
    const completeIndicator = document.getElementById('stepIndicatorComplete');
    if (completeIndicator) {
        completeIndicator.style.display = 'flex';
    }

    // 質問完了エリアを表示
    const interviewCompleteArea = document.getElementById('interviewCompleteArea');
    if (interviewCompleteArea) {
        interviewCompleteArea.style.display = 'block';
    }

    console.log('すべての質問が完了しました');
}

// 録り直しボタン
document.getElementById('retakeBtn').addEventListener('click', async () => {
    if (!confirm('録り直しを行いますか？現在の録画データは削除されます。')) {
        return;
    }

    try {
        const response = await fetch('{{ route("record.retake") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                token: '{{ $token }}'
            })
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            // セルフ面接のやり方画面に戻る
            document.getElementById('interviewCompleteArea').style.display = 'none';
            document.getElementById('howToArea').style.display = 'block';

            // STEPインジケーターをステップ2に戻す
            updateStepIndicator(2);

            // 録り直し回数を更新
            document.getElementById('retakeCount').textContent = data.remaining_retakes;

            // 変数をリセット
            currentQuestionIndex = 0;
            recordedChunks = [];
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('録り直しエラー:', error);
        alert('録り直しの開始に失敗しました。');
    }
});

// プレビューボタン
document.getElementById('previewAllBtn').addEventListener('click', async () => {
    try {
        const response = await fetch('{{ route("record.preview") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                token: '{{ $token }}'
            })
        });

        const data = await response.json();

        if (data.success) {
            console.log('プレビューデータ:', data);

            // プレビューウィンドウを開く
            const previewWindow = window.open('', '_blank', 'width=800,height=600');

            let videoHtml = `
                <html>
                    <head><title>面接動画プレビュー</title></head>
                    <body style="margin:0; padding:20px; background:#000;">
                        <h2 style="color:white; text-align:center;">面接動画プレビュー</h2>
                        <div style="max-width:600px; margin:0 auto;">
            `;

            data.videos.forEach((video, index) => {
                console.log(`動画${index + 1}:`, video);

                videoHtml += `
                    <div style="margin-bottom:20px; background:white; padding:10px; border-radius:8px;">
                        <h3>質問${index + 1}: ${video.question_text}</h3>
                        <p style="font-size:12px; color:#666;">
                            ファイル名: ${video.filename}<br>
                            カスタムURL: ${video.video_path}<br>
                            AssetURL: ${video.asset_path}<br>
                            存在確認: storage=${video.file_exists}, public=${video.public_exists}<br>
                            ファイルサイズ: ${video.file_size} bytes<br>
                            MIMEタイプ: ${video.mime_type}
                        </p>
                        <video controls style="width:100%;" onerror="console.error('動画読み込みエラー:', this.src)">
                            <source src="${video.video_path}" type="video/webm">
                            <source src="${video.video_path}" type="video/mp4">
                            <source src="${video.video_path}" type="video/mov">
                            動画を読み込めませんでした: ${video.video_path}
                        </video>
                    </div>
                `;
            });

            videoHtml += `
                        </div>
                    </body>
                </html>
            `;

            previewWindow.document.write(videoHtml);
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('プレビューエラー:', error);
        alert('プレビューの表示に失敗しました。');
    }
});

// 送信するボタン
document.getElementById('submitAllBtn').addEventListener('click', async () => {
    if (!confirm('面接動画を送信しますか？')) {
        return;
    }

    // ボタンを無効化して二重送信防止
    const submitBtn = document.getElementById('submitAllBtn');
    submitBtn.disabled = true;
    submitBtn.textContent = '送信中...';

    try {
        const response = await fetch('{{ route("record.submit") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                token: '{{ $token }}'
            })
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            window.location.href = '{{ route("record.complete") }}';
        } else {
            console.error('送信失敗:', data);
            alert('送信失敗: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.textContent = '送信する';
        }
    } catch (error) {
        console.error('送信エラー:', error);
        alert('送信に失敗しました。');
        submitBtn.disabled = false;
        submitBtn.textContent = '送信する';
    }
});

// 秒読みオーバーレイ表示関数
function showCountdownOverlay(callback) {
    const overlay = document.getElementById('countdownOverlay');
    const numberElement = document.getElementById('countdownNumber');
    const questionTimer = document.getElementById('questionTimer');

    if (!overlay || !numberElement) return;

    // オーバーレイを表示
    overlay.style.display = 'flex';

    // 質問タイマーに待機中を表示
    if (questionTimer) {
        questionTimer.textContent = '待機中...';
    }

    let countdown = 2; // 2秒カウントダウン

    // 最初の数字を表示
    numberElement.textContent = countdown;

    const countdownInterval = setInterval(() => {
        countdown--;

        if (countdown > 0) {
            numberElement.textContent = countdown;
        } else {
            // カウントダウン終了
            clearInterval(countdownInterval);
            overlay.style.display = 'none';

            // コールバック実行
            if (callback) {
                callback();
            }
        }
    }, 1000);
}

// ページ初期化
window.addEventListener('load', () => {
    // 初期状態では面接エリア、やり方エリア、準備完了エリア、プログレスステップ、完了エリアは非表示
    if (document.getElementById('interviewArea')) {
        document.getElementById('interviewArea').style.display = 'none';
    }
    if (document.getElementById('howToArea')) {
        document.getElementById('howToArea').style.display = 'none';
    }
    if (document.getElementById('readyArea')) {
        document.getElementById('readyArea').style.display = 'none';
    }
    if (document.getElementById('progressSteps')) {
        document.getElementById('progressSteps').style.display = 'none';
    }
    if (document.getElementById('interviewCompleteArea')) {
        document.getElementById('interviewCompleteArea').style.display = 'none';
    }

    // 初期状態でSTEP1のみを表示
    for (let i = 2; i <= 5; i++) {
        const indicator = document.getElementById(`stepIndicator${i}`);
        if (indicator) {
            indicator.style.display = 'none';
        }
    }
    const completeIndicator = document.getElementById('stepIndicatorComplete');
    if (completeIndicator) {
        completeIndicator.style.display = 'none';
    }

    // STEP1を確実に表示
    const step1Indicator = document.getElementById('stepIndicator1');
    if (step1Indicator) {
        step1Indicator.style.display = 'flex';
    }
});
</script>
@endif
@endsection
