@extends('layouts.master')

@section('title', 'カテゴリー詳細')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- ヘッダー -->
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('master.category.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-red-600">
                            カテゴリー管理
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">詳細</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="mt-2 md:flex md:items-center md:justify-between">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ $category->category_name }}
                </h1>
                <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                    <a href="{{ route('master.category.edit', $category->category_id) }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        編集
                    </a>
                </div>
            </div>
        </div>

        <!-- カテゴリー基本情報カード -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">基本情報</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">カテゴリーID</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $category->category_id }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">表示順序</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $category->category_order }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">スラッグ</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $category->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">カテゴリーグループ</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($category->category_group)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                    {{ $category->category_group }}
                                </span>
                            @else
                                <span class="text-gray-400">未設定</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- 関連質問一覧カード -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        関連質問一覧
                        <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $category->questions->count() }}件
                        </span>
                    </h3>
                    @if($category->questions->count() > 0)
                        <a href="{{ route('master.question.index', ['category_id' => $category->category_id]) }}"
                           class="text-sm text-red-600 hover:text-red-900">
                            すべて表示 →
                        </a>
                    @endif
                </div>
            </div>

            @if($category->questions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">順序</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">質問内容</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">メモ</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($category->questions->sortBy('order') as $question)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $question->order }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div class="max-w-md">
                                            {{ Str::limit($question->q, 80) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <div class="max-w-xs">
                                            {{ $question->memo ? Str::limit($question->memo, 50) : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('master.question.show', $question->question_id) }}" class="text-blue-600 hover:text-blue-900">
                                                詳細
                                            </a>
                                            <a href="{{ route('master.question.edit', $question->question_id) }}" class="text-red-600 hover:text-red-900">
                                                編集
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-4 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">質問がありません</h3>
                    <p class="mt-1 text-sm text-gray-500">このカテゴリーに質問を追加してください。</p>
                    <div class="mt-6">
                        <a href="{{ route('master.question.create', ['category_id' => $category->category_id]) }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            質問を追加
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- アクションボタン -->
        <div class="mt-6 flex justify-between">
            <a href="{{ route('master.category.index') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                一覧に戻る
            </a>
            <div class="flex space-x-3">
                <a href="{{ route('master.category.edit', $category->category_id) }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                    編集
                </a>
                @if($category->questions->count() == 0)
                    <form method="POST" action="{{ route('master.category.destroy', $category->category_id) }}"
                          onsubmit="return confirm('このカテゴリーを削除してもよろしいですか？')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            削除
                        </button>
                    </form>
                @else
                    <span class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-400 cursor-not-allowed"
                          title="質問が関連付けられているため削除できません">
                        削除
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
