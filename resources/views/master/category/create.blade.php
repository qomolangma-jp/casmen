@extends('layouts.master')

@section('title', 'カテゴリー作成')

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
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">新規作成</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                新規カテゴリー作成
            </h1>
        </div>

        <!-- フォーム -->
        <div class="bg-white shadow rounded-lg">
            <form method="POST" action="{{ route('master.category.store') }}" class="space-y-6">
                @csrf

                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- カテゴリー名 -->
                        <div>
                            <label for="category_name" class="block text-sm font-medium text-gray-700">
                                カテゴリー名 <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="category_name" name="category_name" value="{{ old('category_name') }}" required
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
                            <input type="text" id="slug" name="slug" value="{{ old('slug') }}" required
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
                                <input type="text" id="category_group" name="category_group" value="{{ old('category_group') }}"
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
                            <input type="number" id="category_order" name="category_order" min="0" value="{{ old('category_order', 0) }}" required
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
                        作成する
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// カテゴリー名からスラッグを自動生成
document.getElementById('category_name').addEventListener('input', function() {
    const categoryName = this.value;
    const slugField = document.getElementById('slug');

    if (categoryName && !slugField.value) {
        // カタカナ・ひらがな・漢字を削除し、英数字とスペースのみ残す
        let slug = categoryName
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');

        // 日本語の場合は空にする（手動入力を促す）
        if (slug === '') {
            slug = '';
        }

        slugField.value = slug;
    }
});
</script>
@endsection
