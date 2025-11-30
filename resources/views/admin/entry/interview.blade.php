@extends('layouts.admin')

@section('title', 'CASMEN｜面接URL管理')

@section('content')
<main>
    <div class="main-container">
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- 戻るボタン -->
                    <div class="mb-6">
                        <a href="{{ route('admin.entry.show', $entry->entry_id) }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            ← 応募者詳細に戻る
                        </a>
                    </div>

                    <!-- 応募者情報 -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="font-semibold text-lg mb-2">応募者情報</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">氏名:</span>
                                <span class="text-gray-900">{{ $entry->name }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">メール:</span>
                                <span class="text-gray-900">{{ $entry->email }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">ステータス:</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($entry->status === '完了')
                                        bg-green-100 text-green-800
                                    @elseif($entry->status === '録画中')
                                        bg-yellow-100 text-yellow-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif
                                ">
                                    {{ $entry->status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- 面接URL情報 -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <h3 class="font-semibold text-lg mb-4 text-blue-900">面接URL情報</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-blue-800 mb-2">面接UUID</label>
                                <div class="flex items-center space-x-2">
                                    <input type="text"
                                           id="interview-uuid"
                                           value="{{ $entry->interview_uuid }}"
                                           readonly
                                           class="flex-1 px-3 py-2 bg-white border border-blue-300 rounded-md text-sm font-mono">
                                    <button onclick="copyToClipboard('interview-uuid')"
                                            class="px-3 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        コピー
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-blue-800 mb-2">完全な面接URL</label>
                                <div class="flex items-center space-x-2">
                                    <input type="text"
                                           id="interview-url"
                                           value="{{ config('app.url') }}/interview/{{ $entry->interview_uuid }}"
                                           readonly
                                           class="flex-1 px-3 py-2 bg-white border border-blue-300 rounded-md text-sm">
                                    <button onclick="copyToClipboard('interview-url')"
                                            class="px-3 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        コピー
                                    </button>
                                </div>
                            </div>

                            <div class="bg-white p-4 rounded-md border border-blue-200">
                                <h4 class="font-medium text-blue-900 mb-2">URLの使用方法</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• このURLを応募者にメールで送信してください</li>
                                    <li>• 応募者はこのURLにアクセスして面接動画を録画します</li>
                                    <li>• URLは応募者専用で、他の方はアクセスできません</li>
                                    <li>• 面接完了後はステータスが「完了」に更新されます</li>
                                </ul>
                            </div>
                        </div>

                        <!-- アクションボタン -->
                        <div class="mt-6 flex space-x-4">
                            <a href="{{ config('app.url') }}/interview/{{ $entry->interview_uuid }}"
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                URLをテスト
                            </a>

                            <button type="button"
                                    onclick="sendInterviewEmail()"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                面接案内メール送信
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            element.select();
            element.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(element.value).then(() => {
                // 成功時の処理
                const button = element.nextElementSibling;
                const originalText = button.textContent;
                button.textContent = 'コピー済み';
                button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                button.classList.add('bg-green-600', 'hover:bg-green-700');

                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('bg-green-600', 'hover:bg-green-700');
                    button.classList.add('bg-blue-600', 'hover:bg-blue-700');
                }, 2000);
            });
        }

        function sendInterviewEmail() {
            if (confirm('{{ $entry->name }}さんに面接案内メールを送信しますか？')) {
                // TODO: メール送信のAjax処理を実装
                alert('メール送信機能は今後実装予定です');
            }
        }
    </script>
    </div>
</main>
@endsection
