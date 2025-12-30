@extends('layouts.admin')

@section('title', 'CASMEN｜お知らせ一覧')

@section('content')
<main>
    <div class="main-container">
        <div class="breadcrumbs dashboard-breadcrumbs">
            <span><a href="{{ route('admin.dashboard') }}">TOP</a> > お知らせ一覧</span>
        </div>
        <div class="ray-content notice">
            <span class="notice-title">お知らせ</span>
            <ul class="notice-list">
                @forelse ($notices as $notice)
                    <li>
                        <a href="{{ route('admin.notice.show', $notice->notice_id) }}">
                            <div class="notice-text">
                                {{-- NEW badge logic: created within 3 days --}}
                                @if(\Carbon\Carbon::parse($notice->created_at)->diffInDays(now()) < 3)
                                    <span class="new">{{ $notice->title }}</span>
                                @else
                                    <span>{{ $notice->title }}</span>
                                @endif
                                <time datetime="{{ $notice->created_at }}">{{ \Carbon\Carbon::parse($notice->created_at)->format('Y年m月d日 H時i分') }}</time>
                            </div>
                            <img src="{{ asset('assets/admin/img/right-chevron.png') }}" alt="右アイコン">
                        </a>
                    </li>
                @empty
                    <li>
                        <div class="notice-text">
                            <span>お知らせはありません。</span>
                        </div>
                    </li>
                @endforelse
            </ul>

            {{-- Pagination --}}
            @if ($notices->hasPages())
                <div class="paging paging-gray">
                        {{ $notices->links('vendor.pagination.custom') }}
                </div>
            @endif
        </div>
        <div class="privacy-policy">
            <a href="{{ route('company.policy') }}" target="_blank">個人情報の取り扱いについて</a>
        </div>
    </div>
</main>
@endsection
