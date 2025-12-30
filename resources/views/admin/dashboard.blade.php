@extends('layouts.admin')

@section('title', 'CASMEN｜ダッシュボード')

@section('content')
<main>
    <div class="main-container">
        <div class="breadcrumbs">
            <span>ダッシュボード</span>
        </div>
        <div class="ray-content">
            <!-- 正常に表示される場合 -->
            <ul class="dashboard">
                <li>
                    <a href="{{ route('admin.link.create') }}">
                        <figure>
                            <img src="{{ asset('assets/admin/img/people.png') }}" alt="人">
                        </figure>
                        <div class="btn-content">
                            <div class="btn-content-inner">
                                <span class="btn-title">面接URL発行</span>
                                <span class="btn-description">応募者を登録し、面接URLを発行</span>
                            </div>
                            <img src="{{ asset('assets/admin/img/right-chevron.png') }}" alt="右アイコン">
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.entry.index') }}">
                        <figure>
                            <img src="{{ asset('assets/admin/img/people.png') }}" alt="人">
                        </figure>
                        <div class="btn-content">
                            <div class="btn-content-inner">
                                <span class="btn-title">応募者一覧</span>
                                <span class="btn-description">評価を行う@if(isset($waitingCount) && $waitingCount > 0)<span class="waiting-list">【評価待ち{{ $waitingCount }}人】</span>@endif</span>
                            </div>
                            <img src="{{ asset('assets/admin/img/right-chevron.png') }}" alt="右アイコン">
                        </div>
                    </a>
                </li>
            </ul>

            <div class="notice dashboard-notice">
                <span class="notice-title">お知らせ</span>
                <ul class="notice-list">
                    @forelse ($notices ?? [] as $notice)
                        <li>
                            <a href="{{ route('admin.notice.show', $notice->notice_id) }}">
                                <div class="notice-text">
                                    @if($notice->created_at && $notice->created_at->diffInDays(now()) < 7)
                                        <span class="new">{{ Str::limit($notice->title, 30) }}</span>
                                    @else
                                        <span>{{ Str::limit($notice->title, 30) }}</span>
                                    @endif
                                    <time datetime="{{ $notice->created_at ? $notice->created_at->format('Y-m-d\TH:i') : '' }}">
                                        {{ $notice->created_at ? $notice->created_at->format('Y年m月d日 H時i分') : '' }}
                                    </time>
                                </div>
                                <img src="{{ asset('assets/admin/img/right-chevron.png') }}" alt="右アイコン">
                            </a>
                        </li>
                    @empty
                        <li>
                            <div class="notice-text" style="padding: 1rem; text-align: center; color: #666;">
                                お知らせはありません
                            </div>
                        </li>
                    @endforelse
                </ul>
                <span class="index">
                    <a href="{{ route('admin.notice.index') }}">
                        <span>一覧</span>
                        <img src="{{ asset('assets/admin/img/right-chevron.png') }}" alt="右アイコン">
                    </a>
                </span>
            </div>
        </div>
        {{-- <x-admin-privacy-link /> --}}
    </div>
</main>
@endsection
