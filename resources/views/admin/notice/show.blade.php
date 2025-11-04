<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('お知らせ詳細') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- 戻るボタン -->
                    <div class="mb-6">
                        <a href="{{ route('admin.notice.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            ← 一覧に戻る
                        </a>
                    </div>

                    <!-- お知らせ詳細 -->
                    <div class="space-y-6">
                        <!-- タイトル -->
                        <div class="border-b border-gray-200 pb-4">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $notice->title }}</h1>
                            <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                <span>ID: {{ $notice->notice_id }}</span>
                                <span>作成日時: {{ \Carbon\Carbon::parse($notice->created_at)->format('Y年m月d日 H:i') }}</span>
                                @if($notice->updated_at != $notice->created_at)
                                    <span>更新日時: {{ \Carbon\Carbon::parse($notice->updated_at)->format('Y年m月d日 H:i') }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- カテゴリ -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">カテゴリ</label>
                            @if($notice->category_name)
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $notice->category_name }}
                                </span>
                            @else
                                <span class="text-gray-400">カテゴリなし</span>
                            @endif
                        </div>

                        <!-- 本文 -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">本文</label>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="prose max-w-none">
                                    {!! nl2br(e($notice->text)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
