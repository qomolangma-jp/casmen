@extends('layouts.admin')

@section('title', 'CASMEN｜テスト用お知らせ')

@section('content')
<main>
    <div class="main-container">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3>お知らせ管理（テスト）</h3>
                    <p>これはテスト用のシンプルなビューです。</p>

                    @if(count($notices) > 0)
                        <p>お知らせが {{ count($notices) }} 件あります。</p>
                    @else
                        <p>現在お知らせはありません。</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</main>
@endsection
