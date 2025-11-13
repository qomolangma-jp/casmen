@extends('layouts.interview')

@section('title', '面接完了')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- 完了メッセージ -->
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="mb-6">
                <svg class="w-16 h-16 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-4">面接完了</h1>
            <p class="text-lg text-gray-600 mb-6">
                面接動画のアップロードが完了しました。<br>
                お疲れ様でした！
            </p>

            <div class="bg-green-50 rounded-lg p-4 mb-6">
                <h3 class="text-green-800 font-semibold mb-2">次のステップ</h3>
                <p class="text-green-700 text-sm">
                    採用担当者が動画を確認後、結果をメールでお知らせいたします。<br>
                    しばらくお待ちください。
                </p>
            </div>

            <div class="space-y-4">
                <a href="{{ route('top.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200">
                    トップページに戻る
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
