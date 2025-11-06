@extends('layouts.master')

@section('title', 'カテゴリー編集')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
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
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">編集</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                カテゴリー編集: {{ $category->category_name }}
            </h1>
        </div>

        <!-- フォーム -->
        <div class="bg-white shadow rounded-lg">
            <form method="POST" action="{{ route('master.category.update', $category->category_id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- カテゴリー名 -->
                        <div>
                            <label for="category_name" class="block text-sm font-medium text-gray-700">
                                カテゴリー名 <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="category_name" name="category_name" value="{{ old('category_name', $category->category_name) }}" required
                                   placeholder="カテゴリー名を入力してください"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 @error('category_name') border-red-300 @enderror">
                            @error('category_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- スラッグ -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700">
                                スラッグ <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="slug" name="slug" value="{{ old('slug', $category->slug) }}" required
                                   placeholder="URLで使用されるスラッグ（例：basic-questions）"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 @error('slug') border-red-300 @enderror">
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">英数字とハイフンのみ使用可能。一意である必要があります。</p>
                        </div>

                        <!-- カテゴリーグループ -->
                        <div>
                            <label for="category_group" class="block text-sm font-medium text-gray-700">
                                カテゴリーグループ
                            </label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="text" id="category_group" name="category_group" value="{{ old('category_group', $category->category_group) }}"
                                       placeholder="グループ名を入力（例：基本質問、専門質問）"
                                       class="flex-1 border-gray-300 rounded-l-md focus:ring-red-500 focus:border-red-500 @error('category_group') border-red-300 @enderror"
                                       list="existing-groups">
                                <datalist id="existing-groups">
                                    @foreach($existingGroups as $group)
                                        <option value="{{ $group }}">
                                    @endforeach
                                </datalist>
                            </div>
                            @error('category_group')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">既存のグループを選択するか、新しいグループ名を入力してください。</p>
                        </div>

                        <!-- 表示順序 -->
                        <div>
                            <label for="category_order" class="block text-sm font-medium text-gray-700">
                                表示順序 <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="category_order" name="category_order" min="0" value="{{ old('category_order', $category->category_order) }}" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 @error('category_order') border-red-300 @enderror">
                            @error('category_order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">数値が小さいほど上位に表示されます。</p>
                        </div>
                    </div>
                </div>

                <!-- ボタン -->
                <div class="px-4 py-4 sm:px-6 bg-gray-50 flex justify-end space-x-3">
                    <a href="{{ route('master.category.index') }}"
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

        <!-- 関連質問情報 -->
        @if($category->questions->count() > 0)
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg">
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
                            <p>このカテゴリーには{{ $category->questions->count() }}件の質問が関連付けられています。</p>
                            <p>カテゴリー名やグループを変更すると、関連する質問の表示にも影響します。</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
