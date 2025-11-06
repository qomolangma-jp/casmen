<x-master-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('マスター管理画面') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-red-600 mb-2">マスター権限でログイン中</h3>
                        <p class="text-gray-600">システム全体の管理が可能です。</p>
                    </div>

                    <!-- 管理メニュー -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- 登録店舗管理 -->
                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-blue-500 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <h4 class="ml-3 text-lg font-semibold text-gray-900">登録店舗</h4>
                            </div>
                            <p class="text-gray-600 mb-4">登録されている店舗の一覧と詳細を確認できます。</p>
                            <a href="{{ route('master.shop.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                店舗一覧
                            </a>
                        </div>

                        <!-- 面接URL管理 -->
                        <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-green-500 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                </div>
                                <h4 class="ml-3 text-lg font-semibold text-gray-900">面接URL</h4>
                            </div>
                            <p class="text-gray-600 mb-4">発行された面接URLの状態を一覧で確認できます。</p>
                            <a href="{{ route('master.link.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                                URL一覧
                            </a>
                        </div>

                        <!-- 質問管理 -->
                        <div class="bg-cyan-50 p-6 rounded-lg border border-cyan-200">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-cyan-500 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="ml-3 text-lg font-semibold text-gray-900">質問管理</h4>
                            </div>
                            <p class="text-gray-600 mb-4">面接で使用する質問の作成・編集・削除ができます。</p>
                            <div class="space-x-2">
                                <a href="{{ route('master.question.index') }}" class="inline-flex items-center px-4 py-2 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-700 transition">
                                    一覧
                                </a>
                                <a href="{{ route('master.question.create') }}" class="inline-flex items-center px-4 py-2 bg-cyan-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-900 transition">
                                    新規作成
                                </a>
                            </div>
                        </div>

                        <!-- カテゴリー管理 -->
                        <div class="bg-red-50 p-6 rounded-lg border border-red-200">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-red-500 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <h4 class="ml-3 text-lg font-semibold text-gray-900">カテゴリー管理</h4>
                            </div>
                            <p class="text-gray-600 mb-4">質問のカテゴリー分類の作成・編集・削除ができます。</p>
                            <div class="space-x-2">
                                <a href="{{ route('master.category.index') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition">
                                    一覧
                                </a>
                                <a href="{{ route('master.category.create') }}" class="inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-900 transition">
                                    新規作成
                                </a>
                            </div>
                        </div>

                        <!-- お知らせ管理 -->
                        <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-200">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-yellow-500 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                    </svg>
                                </div>
                                <h4 class="ml-3 text-lg font-semibold text-gray-900">お知らせ管理</h4>
                            </div>
                            <p class="text-gray-600 mb-4">お知らせの作成・編集・削除ができます。</p>
                            <div class="space-x-2">
                                <a href="{{ route('master.notice.index') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 transition">
                                    一覧
                                </a>
                                <a href="{{ route('master.notice.create') }}" class="inline-flex items-center px-4 py-2 bg-yellow-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-900 transition">
                                    新規作成
                                </a>
                            </div>
                        </div>

                        <!-- システム統計 -->
                        <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-purple-500 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <h4 class="ml-3 text-lg font-semibold text-gray-900">システム統計</h4>
                            </div>
                            <p class="text-gray-600 mb-4">システム全体の利用状況を確認できます。</p>
                            <button class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition">
                                詳細を見る
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-master-layout>
