@extends('layouts.master')

@section('title', '質問編集')

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
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">編集</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                質問編集
            </h1>
        </div>

        <!-- フォーム -->
        <div class="bg-white shadow rounded-lg">
            <form method="POST" action="{{ route('master.question.update', $question->question_id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- カテゴリー選択 -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">
                                カテゴリー <span class="text-red-500">*</span>
                            </label>
                            <select id="category_id" name="category_id" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 @error('category_id') border-red-300 @enderror">
                                <option value="">カテゴリーを選択してください</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->category_id }}"
                                            {{ (old('category_id', $question->category_id) == $category->category_id) ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                        @if($category->category_group)
                                            ({{ $category->category_group }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 質問内容 -->
                        <div>
                            <label for="q" class="block text-sm font-medium text-gray-700">
                                質問内容 <span class="text-red-500">*</span>
                            </label>
                            <textarea id="q" name="q" rows="4" required
                                      placeholder="面接で使用する質問を入力してください..."
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 @error('q') border-red-300 @enderror">{{ old('q', $question->q) }}</textarea>
                            @error('q')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">最大500文字まで入力できます。</p>
                        </div>

                        <!-- メモ -->
                        <div>
                            <label for="memo" class="block text-sm font-medium text-gray-700">
                                メモ
                            </label>
                            <textarea id="memo" name="memo" rows="3"
                                      placeholder="質問に関するメモや注意事項を入力してください..."
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 @error('memo') border-red-300 @enderror">{{ old('memo', $question->memo) }}</textarea>
                            @error('memo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">最大1000文字まで入力できます。</p>
                        </div>

                        <!-- 表示順序 -->
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700">
                                表示順序 <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="order" name="order" min="0" value="{{ old('order', $question->order) }}" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 @error('order') border-red-300 @enderror">
                            @error('order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">数値が小さいほど上位に表示されます。</p>
                        </div>
                    </div>
                </div>

                <!-- ボタン -->
                <div class="px-4 py-4 sm:px-6 bg-gray-50 flex justify-end space-x-3">
                    <a href="{{ route('master.question.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        キャンセル
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        更新する
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
