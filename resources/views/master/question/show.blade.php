@extends('layouts.master')

@section('title', '質問詳細')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- ヘッダー -->
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('master.question.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-red-600">
                            質問管理
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
                    質問詳細
                </h1>
                <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                    <a href="{{ route('master.question.edit', $question->question_id) }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        編集
                    </a>
                </div>
            </div>
        </div>

        <!-- 質問情報カード -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">基本情報</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">質問ID</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $question->question_id }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">表示順序</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $question->order }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">カテゴリー</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($question->category)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $question->category->category_name }}
                                </span>
                                @if($question->category->category_group)
                                    <span class="ml-2 text-gray-500 text-xs">
                                        ({{ $question->category->category_group }})
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-400">未設定</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- 質問内容カード -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">質問内容</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="prose max-w-none">
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $question->q }}</p>
                </div>
            </div>
        </div>

        @if($question->memo)
        <!-- メモカード -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">メモ</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="prose max-w-none">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $question->memo }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- アクションボタン -->
        <div class="flex justify-between">
            <a href="{{ route('master.question.index') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                一覧に戻る
            </a>
            <div class="flex space-x-3">
                <a href="{{ route('master.question.edit', $question->question_id) }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                    編集
                </a>
                <form method="POST" action="{{ route('master.question.destroy', $question->question_id) }}"
                      onsubmit="return confirm('この質問を削除してもよろしいですか？')" class="inline">
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
            </div>
        </div>
    </div>
</div>
@endsection
