@extends('layouts.admin')

@section('title', 'CASMEN｜お知らせ一覧')

@section('content')
<main>
    <div class="main-container">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-medium">お知らせ管理</h3>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded">
                            新規作成
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        ID
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        タイトル
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        本文（抜粋）
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        作成日時
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        操作
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($notices as $notice)
                                    <tr>
                                        <td class="px-6 py-4 text-sm">{{ $notice->notice_id }}</td>
                                        <td class="px-6 py-4 text-sm font-medium">{{ $notice->title }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ Str::limit($notice->text, 50) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $notice->created_at ? \Carbon\Carbon::parse($notice->created_at)->format('Y/m/d H:i') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <a href="{{ route('admin.notice.show', $notice->notice_id) }}" class="text-blue-600 hover:text-blue-800">詳細</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            お知らせデータがありません
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- ページネーション -->
                    @if($notices->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    {{ $notices->firstItem() ?? 0 }}〜{{ $notices->lastItem() ?? 0 }}件 / 全{{ $notices->total() }}件
                                </div>
                                <div>
                                    {{ $notices->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</main>
@endsection
