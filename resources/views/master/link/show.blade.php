@extends('layouts.master')

@section('title', '面接URL詳細')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- ヘッダー -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('master.link.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-red-600">
                                面接URL管理
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">URL詳細</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ $link->name ?? 'URL詳細' }}
                </h1>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <button type="button" onclick="copyToClipboard('{{ $link->interview_url }}')" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    URLコピー
                </button>
                @if($link->video_path)
                    <button type="button" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m2-10V7a2 2 0 00-2-2H9a2 2 0 00-2 2v3m12 0a2 2 0 010 4H9a2 2 0 01-2-2v-3m12 0V7"></path>
                        </svg>
                        面接動画確認
                    </button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- 左カラム: 基本情報 -->
            <div class="lg:col-span-2 space-y-6">
                <!-- 応募者情報カード -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">応募者情報</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">氏名</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $link->name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">メールアドレス</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $link->email ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">電話番号</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $link->phone ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">年齢</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $link->age ?? 'N/A' }}歳</dd>
                            </div>
                            @if($link->user)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">店舗名</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $link->user->shop_name ?? 'N/A' }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- URL情報カード -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">URL情報</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">面接URL</dt>
                                <dd class="mt-1 text-sm text-gray-900 break-all">
                                    <div class="flex items-center space-x-2">
                                        <span class="flex-1">{{ $link->interview_url }}</span>
                                        <button type="button" onclick="copyToClipboard('{{ $link->interview_url }}')" class="text-red-600 hover:text-red-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">トークン</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $link->interview_token }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">有効期限</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($link->expires_at)
                                        {{ $link->expires_at->format('Y年m月d日 H:i') }}
                                        @if($link->is_expired)
                                            <span class="ml-2 text-red-600">(期限切れ)</span>
                                        @else
                                            <span class="ml-2 text-green-600">(有効)</span>
                                        @endif
                                    @else
                                        無期限
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                @if($link->video_path)
                <!-- 面接結果カード -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">面接結果</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">動画ファイル</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ basename($link->video_path) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">録画完了日時</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $link->updated_at->format('Y年m月d日 H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
                @endif
            </div>

            <!-- 右カラム: ステータス・操作 -->
            <div class="space-y-6">
                <!-- ステータスカード -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">ステータス</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="space-y-4">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-500">現在のステータス</span>
                                    @if($link->is_completed)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            面接完了
                                        </span>
                                    @elseif($link->is_expired)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            期限切れ
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            有効
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-500">発行日</span>
                                    <span class="text-sm text-gray-900">
                                        {{ $link->created_at->format('Y年m月d日') }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-500">最終更新</span>
                                    <span class="text-sm text-gray-900">
                                        {{ $link->updated_at->format('Y年m月d日') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- クイックアクション -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">クイックアクション</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="space-y-3">
                            <button type="button" onclick="sendReminderEmail()" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                リマインダー送信
                            </button>
                            <button type="button" onclick="extendExpiration()" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                有効期限延長
                            </button>
                            <button type="button" onclick="disableUrl()" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                </svg>
                                URL無効化
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 注意事項 -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="px-4 py-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">注意事項</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>URLは他者と共有しないでください</li>
                                        <li>期限切れ後は面接を受けることができません</li>
                                        <li>面接完了後はURLは自動的に無効化されます</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// URLをクリップボードにコピー
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('URLをクリップボードにコピーしました');
    }).catch(function(err) {
        console.error('コピーに失敗しました: ', err);
    });
}

// リマインダーメール送信
function sendReminderEmail() {
    if (confirm('リマインダーメールを送信しますか？')) {
        // AJAX でリマインダー送信処理を実装
        alert('リマインダーメールを送信しました');
    }
}

// 有効期限延長
function extendExpiration() {
    const days = prompt('何日間延長しますか？', '7');
    if (days && !isNaN(days)) {
        // AJAX で期限延長処理を実装
        alert(`有効期限を${days}日間延長しました`);
    }
}

// URL無効化
function disableUrl() {
    if (confirm('このURLを無効化しますか？\nこの操作は取り消せません。')) {
        // AJAX でURL無効化処理を実装
        alert('URLを無効化しました');
    }
}
</script>
@endsection
