@extends('layouts.admin')

@section('title', 'CASMEN｜ダッシュボード')

@section('content')
<main>
    <div class="main-container">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- ログイン成功メッセージ -->
            @if(session('login_success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-green-800 font-medium">{{ session('login_success') }}</span>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-blue-600 mb-2">店舗管理画面へようこそ</h3>
                        <p class="text-gray-600">店舗運営に必要な機能をご利用いただけます。</p>

                        <!-- テスト用ボタンセット -->
                        <div class="mt-4 p-4 bg-yellow-100 border border-yellow-300 rounded">
                            <h4 class="text-sm font-bold text-yellow-800 mb-2">Tailwind色テスト</h4>
                            <div class="space-x-2">
                                <button class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">赤</button>
                                <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">青</button>
                                <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">緑</button>
                                <button class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">黄</button>
                                <button class="px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600">紫</button>
                            </div>
                        </div>
                    </div>

                    <!-- 管理メニュー -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- 面接URL発行 -->
                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-blue-500 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                </div>
                                <h4 class="ml-3 text-lg font-semibold text-gray-900">面接URL発行</h4>
                            </div>
                            <p class="text-gray-600 mb-4">求職者向けの面接URLを発行できます。</p>
                            <a href="{{ route('admin.link.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                URL発行
                            </a>
                        </div>

                        <!-- お知らせ -->
                        <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-green-500 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                    </svg>
                                </div>
                                <h4 class="ml-3 text-lg font-semibold text-gray-900">お知らせ</h4>
                            </div>
                            <p class="text-gray-600 mb-4">運営からのお知らせを確認できます。</p>
                            <a href="{{ route('admin.notice.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                                お知らせ一覧
                            </a>
                        </div>

                        <!-- 応募者管理 -->
                        <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-200">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-yellow-500 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="ml-3 text-lg font-semibold text-gray-900">応募者管理</h4>
                            </div>
                            <p class="text-gray-600 mb-4">応募者の一覧と詳細情報を確認できます。</p>
                            <a href="{{ route('admin.entry.index') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 transition">
                                応募者一覧
                            </a>
                        </div>

                        <!-- 各種設定 -->
                        <div class="bg-purple-50 p-6 rounded-lg border border-purple-200 md:col-span-2 lg:col-span-1">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-purple-500 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="ml-3 text-lg font-semibold text-gray-900">各種設定</h4>
                            </div>
                            <p class="text-gray-600 mb-4">プロフィール情報などの設定を変更できます。</p>
                            <a href="{{ route('admin.setting.index') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition">
                                設定画面
                            </a>
                        </div>
                    </div>

                    <!-- 統計情報 -->
                    <div class="mt-8 bg-gray-50 p-6 rounded-lg">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">今月の統計</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">-</div>
                                <div class="text-sm text-gray-500">発行済みURL</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">-</div>
                                <div class="text-sm text-gray-500">新規応募者</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-yellow-600">-</div>
                                <div class="text-sm text-gray-500">面接完了</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
