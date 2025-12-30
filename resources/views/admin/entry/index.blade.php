@extends('layouts.admin')

@section('title', 'CASMEN｜応募者一覧')

@section('content')
<main>
    <div class="main-container">
        <div class="breadcrumbs">
            <span><a href="{{ route('admin.dashboard') }}">TOP</a> > 応募者一覧</span>
        </div>
        <div class="ray-content applicant-content">
            <div class="applicant-heading">
                <h2>応募者一覧</h2>
                <a href="{{ route('admin.link.create') }}" class="create-url">
                    <img src="{{ asset('assets/admin/img/plus-icon.png') }}" alt="プラスアイコン">
                    <span>面接URL発行</span>
                </a>
            </div>
            <input id="tab1" name="tab" type="radio" checked>
            <input id="tab2" name="tab" type="radio">
            <div class="tabs">
                <label for="tab1">評価待ち</label>
                <label for="tab2">すべて</label>
            </div>
            <ul class="waiting-review-list">
                @forelse ($waitingEntries as $entry)
                    <li>
                        <a href="{{ route('admin.entry.show', $entry->entry_id) }}">
                            <div class="user-status">
                                <div class="status-label">
                                    <span class="review-request">評価を行ってください</span>
                                    <span class="label waiting-review">評価待ち</span>
                                </div>
                                <span>
                                    <span class="user-icon"><img src="{{ asset('assets/admin/img/user-icon.png') }}" alt="ユーザーアイコン"></span>
                                    <span class="user-name">{{ $entry->name }}</span>
                                </span>
                            </div>
                            <div class="user-contact">
                                <ul class="contact-icon-list">
                                    @if($entry->email)
                                        <li><img src="{{ asset('assets/admin/img/email-icon-gray.png') }}" alt="メールアイコン"></li>
                                    @endif
                                    @if($entry->tel)
                                        <li><img src="{{ asset('assets/admin/img/tel-icon.png') }}" alt="TELアイコン"></li>
                                    @endif
                                    @if($entry->video_path)
                                        <li><img src="{{ asset('assets/admin/img/movie-icon.png') }}" alt="動画アイコン"></li>
                                    @endif
                                </ul>
                                @if($entry->completed_at)
                                    <span>動画提出: <time datetime="{{ $entry->completed_at->format('Y-m-d\TH:i') }}">{{ $entry->completed_at->format('Y/m/d H:i') }}</time></span>
                                @endif
                            </div>
                        </a>
                    </li>
                @empty
                    <li>
                        <div class="user-status" style="padding: 2rem; text-align: center; color: #666;">
                            評価待ちの応募者はいません
                        </div>
                    </li>
                @endforelse
            </ul>
            <ul class="all-applicant-list">
                @forelse ($entries as $entry)
                    <li>
                        <a href="{{ route('admin.entry.show', $entry->entry_id) }}">
                            <div class="user-status">
                                <div class="status-label">
                                    @if($entry->status === 'completed' && empty($entry->decision_at))
                                        <span class="review-request">評価を行ってください</span>
                                        <span class="label waiting-review">評価待ち</span>
                                    @elseif($entry->status === 'passed')
                                        <span></span>
                                        <span class="label passed">通過</span>
                                    @elseif($entry->status === 'rejected')
                                        <span></span>
                                        <span class="label rejected">見送り</span>
                                    @elseif(empty($entry->video_path))
                                        <span class="resend-interview-request">面接依頼を再送できます（あと{{ 3 - ($entry->retake_count ?? 0) }}回）</span>
                                        <span class="label not-submitted">未提出</span>
                                    @endif
                                </div>
                                <span>
                                    <span class="user-icon"><img src="{{ asset('assets/admin/img/user-icon.png') }}" alt="ユーザーアイコン"></span>
                                    <span class="user-name">{{ $entry->name }}</span>
                                </span>
                            </div>
                            <div class="user-contact">
                                <ul class="contact-icon-list">
                                    @if($entry->email)
                                        <li><img src="{{ asset('assets/admin/img/email-icon-gray.png') }}" alt="メールアイコン"></li>
                                    @endif
                                    @if($entry->tel)
                                        <li><img src="{{ asset('assets/admin/img/tel-icon.png') }}" alt="TELアイコン"></li>
                                    @endif
                                    @if($entry->video_path)
                                        <li><img src="{{ asset('assets/admin/img/movie-icon.png') }}" alt="動画アイコン"></li>
                                    @endif
                                </ul>
                                @if($entry->completed_at)
                                    <span>動画提出: <time datetime="{{ $entry->completed_at->format('Y-m-d\TH:i') }}">{{ $entry->completed_at->format('Y/m/d H:i') }}</time></span>
                                @endif
                            </div>
                        </a>
                    </li>
                @empty
                    <li>
                        <div class="user-status" style="padding: 2rem; text-align: center; color: #666;">
                            応募者データがありません
                        </div>
                    </li>
                @endforelse
            </ul>
            <div class="paging">
                {{ $entries->links('vendor.pagination.admin') }}
            </div>
        </div>
        <div class="privacy-policy">
            <a href="#">個人情報の取り扱いについて</a>
        </div>
    </div>
</main>
@endsection
