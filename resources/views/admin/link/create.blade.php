@extends('layouts.admin')

@section('title', 'CASMEN｜面接URL発行')

@section('content')
<main>
    <div class="main-container">
    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- メイン コンテンツ -->
            <div class="bg-white rounded-lg shadow-lg p-8">

                <!-- ページタイトル -->
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">面接URL発行</h1>
                    <p class="text-gray-600">求職者の面接動画撮影用URLを発行します</p>
                </div>

                <!-- ステップ進行表示 -->
                <div class="flex items-center justify-center mb-8">
                    <div class="flex items-center space-x-4">
                        <!-- Step 1 -->
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                1
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-700">設定入力</span>
                        </div>

                        <!-- 矢印 -->
                        <div class="w-4 h-0.5 bg-gray-300"></div>

                        <!-- Step 2 -->
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-sm font-bold" id="step2-circle">
                                2
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-500" id="step2-text">URL発行</span>
                        </div>

                        <!-- 矢印 -->
                        <div class="w-4 h-0.5 bg-gray-300"></div>

                        <!-- Step 3 -->
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-sm font-bold" id="step3-circle">
                                3
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-500" id="step3-text">URL共有</span>
                        </div>
                    </div>
                </div>

                <!-- URL発行完了メッセージ -->
                @if (session('interview_url'))
                    <div class="mb-8 p-6 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center mb-4">
                            <div class="w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center mr-3">
                                ✓
                            </div>
                            <h3 class="text-lg font-bold text-green-800">面接URLが正常に発行されました</h3>
                        </div>

                        <!-- 応募者情報表示 -->
                        @if (session('applicant_info'))
                            <div class="bg-white p-4 rounded border border-green-200 mb-4">
                                <h4 class="font-medium text-gray-800 mb-3">応募者情報</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-600">お名前:</span>
                                        <span class="ml-2 text-gray-800">{{ session('applicant_info.name') }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-600">メール:</span>
                                        <span class="ml-2 text-gray-800">{{ session('applicant_info.email') }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-600">電話番号:</span>
                                        <span class="ml-2 text-gray-800">{{ session('applicant_info.phone') }}</span>
                                    </div>
                                </div>
                                <div class="mt-2 text-sm">
                                    <span class="font-medium text-gray-600">有効期限:</span>
                                    <span class="ml-2 text-red-600 font-medium">{{ session('applicant_info.expires_at') }}</span>
                                </div>
                                @if (session('applicant_info.mail_status'))
                                    <div class="mt-2 text-sm">
                                        <span class="font-medium text-gray-600">メール送信:</span>
                                        <span class="ml-2 {{ str_contains(session('applicant_info.mail_status'), '失敗') ? 'text-red-600' : 'text-green-600' }} font-medium">
                                            {{ session('applicant_info.mail_status') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="bg-white p-4 rounded border border-green-200 mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">発行されたURL:</label>
                            <div class="flex items-center space-x-2">
                                <input type="text"
                                       value="{{ session('interview_url') }}"
                                       readonly
                                       id="generated-url"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-sm">
                                <button onclick="copyToClipboard('{{ session('interview_url') }}')"
                                        class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-md transition duration-200">
                                    コピー
                                </button>
                            </div>
                        </div>

                        <div class="bg-blue-50 p-4 rounded border border-blue-200">
                            <h4 class="font-medium text-blue-800 mb-2">次のステップ:</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• 上記URLを応募者（{{ session('applicant_info.name', '応募者') }}）にメールまたはメッセージで送信してください</li>
                                <li>• 応募者はこのURLから面接動画を撮影できます</li>
                                <li>• URLは有効期限内に一度だけ使用できます</li>
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- フォーム -->
                <form method="POST" action="{{ route('admin.link.store') }}" id="url-form">
                    @csrf

                    <div class="space-y-6">
                        <!-- 応募者情報（必須） -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <span class="w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-sm mr-3">*</span>
                                応募者情報（必須）
                            </h3>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        お名前 <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           required
                                           placeholder="例: 田中太郎"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        メールアドレス <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required
                                           placeholder="例: example@example.com"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        電話番号 <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone') }}"
                                           required
                                           placeholder="例: 090-1234-5678"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('phone') border-red-500 @enderror">
                                    <p class="mt-1 text-xs text-gray-500">ハイフン(-)を含めて入力してください</p>
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- 注意事項 -->
                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                            <h4 class="font-semibold text-blue-800 mb-3 flex items-center">
                                <span class="w-5 h-5 text-blue-600 mr-2">ℹ️</span>
                                面接URL発行について
                            </h4>
                            <ul class="text-sm text-blue-700 space-y-2">
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    有効期限は発行日から2週間です（マスター設定による）
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    入力された情報は面接管理に使用されます
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    URLは応募者に直接送信されます
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- アクションボタン -->
                    <div class="mt-8 flex justify-center">
                        <button type="submit"
                                class="px-8 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold text-lg rounded-lg shadow-md transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            面接URLを発行する
                        </button>
                    </div>
                </form>

                <!-- 注意事項 -->
                <div class="mt-8 p-6 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <h4 class="font-semibold text-yellow-800 mb-3 flex items-center">
                        <span class="w-5 h-5 text-yellow-600 mr-2">⚠️</span>
                        ご注意ください
                    </h4>
                    <ul class="text-sm text-yellow-700 space-y-2">
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-yellow-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            発行されたURLは第三者に漏れないよう適切に管理してください
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-yellow-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            面接動画の撮影には安定したインターネット環境が必要です
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-yellow-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            応募者には事前に撮影環境（カメラ・マイク）の確認をお願いしてください
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // コピー成功のフィードバック
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = 'コピー完了!';
                button.classList.remove('bg-green-500', 'hover:bg-green-600');
                button.classList.add('bg-green-600');

                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('bg-green-600');
                    button.classList.add('bg-green-500', 'hover:bg-green-600');
                }, 2000);
            }, function(err) {
                console.error('コピーに失敗しました: ', err);
                alert('コピーに失敗しました。手動でURLをコピーしてください。');
            });
        }

        // フォーム送信時のステップ更新
        document.getElementById('url-form').addEventListener('submit', function() {
            // Step 2をアクティブに
            document.getElementById('step2-circle').classList.remove('bg-gray-300', 'text-gray-500');
            document.getElementById('step2-circle').classList.add('bg-green-500', 'text-white');
            document.getElementById('step2-text').classList.remove('text-gray-500');
            document.getElementById('step2-text').classList.add('text-gray-700');
        });

        @if (session('interview_url'))
        // URL発行完了時のステップ更新
        document.addEventListener('DOMContentLoaded', function() {
            // Step 2をアクティブに
            document.getElementById('step2-circle').classList.remove('bg-gray-300', 'text-gray-500');
            document.getElementById('step2-circle').classList.add('bg-green-500', 'text-white');
            document.getElementById('step2-text').classList.remove('text-gray-500');
            document.getElementById('step2-text').classList.add('text-gray-700');

            // Step 3をアクティブに
            document.getElementById('step3-circle').classList.remove('bg-gray-300', 'text-gray-500');
            document.getElementById('step3-circle').classList.add('bg-green-500', 'text-white');
            document.getElementById('step3-text').classList.remove('text-gray-500');
            document.getElementById('step3-text').classList.add('text-gray-700');
        });
        @endif
    </script>
    </div>
</main>
@endsection
