<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('応募者詳細') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- 戻るボタン -->
                    <div class="mb-6 flex justify-between items-center">
                        <a href="{{ route('admin.entry.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            ← 一覧に戻る
                        </a>

                        <!-- ステータス表示 -->
                        <span class="px-3 py-1 text-sm font-semibold rounded-full
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

                    <!-- 基本情報 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-lg mb-4">基本情報</h3>

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">応募者ID</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $entry->entry_id }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">氏名</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $entry->name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">メールアドレス</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $entry->email }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">電話番号</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $entry->tel ?? '未登録' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-lg mb-4">システム情報</h3>

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">面接UUID</label>
                                    <p class="mt-1 text-sm text-gray-900 font-mono bg-gray-100 p-2 rounded">{{ $entry->interview_uuid }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">登録日時</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $entry->created_at->format('Y年m月d日 H:i') }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">更新日時</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $entry->updated_at->format('Y年m月d日 H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- アクションボタン -->
                    <div class="mt-8 flex space-x-4">
                        <a href="{{ route('admin.entry.interview', $entry->entry_id) }}"
                           class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            面接URL表示
                        </a>

                        <button type="button"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            メール送信
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
