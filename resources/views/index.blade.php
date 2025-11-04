<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOPページ - casmen</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col justify-center items-center">
        <div class="max-w-md w-full bg-white shadow-md rounded-lg p-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">TOPページ</h1>
                <p class="text-lg text-gray-600 mb-8">サービスのプロモーションを掲載</p>

                <div class="space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h2 class="text-xl font-semibold text-blue-800 mb-2">求職者の皆様へ</h2>
                        <p class="text-blue-600">らくらくセルフ面接で簡単に面接を受けられます</p>
                    </div>

                    <div class="flex flex-col space-y-2">
                        <a href="{{ route('record.index') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                            らくらくセルフ面接を始める
                        </a>

                        <a href="{{ route('login') }}"
                           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                            店舗ログイン
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center text-gray-500">
            <p>&copy; 2025 casmen. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
