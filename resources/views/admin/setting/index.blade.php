@extends('layouts.admin')

@section('title', 'CASMEN｜登録情報の確認、変更')

@section('content')
<main>
    <div class="main-container">
        <div class="breadcrumbs">
            <span><a href="{{ route('admin.dashboard') }}">TOP</a> > 各種設定</span>
        </div>
        <div class="ray-content registration-content">
            <h2>登録情報の確認、変更</h2>
            <dl class="registration-info">
                <dt>店舗名</dt>
                <dd>{{ $user->shop_name ?? '未登録' }}</dd>
                <dt>メールアドレス</dt>
                <dd>{{ $user->email }}</dd>
                <dt>携帯電話番号</dt>
                <dd>{{ $user->tel ?? '未登録' }}</dd>
                <dt>住所</dt>
                <dd>
                    @if($user->zip1 && $user->zip2)
                        〒{{ $user->zip1 }}-{{ $user->zip2 }}<br>
                    @endif
                    {{ $user->address ?? '未登録' }}
                </dd>
                <dt>求人情報URL</dt>
                <dd>
                    @if($user->job_url)
                        <a href="{{ $user->job_url }}" target="_blank" rel="noopener noreferrer">{{ $user->job_url }}</a>
                    @else
                        未登録
                    @endif
                </dd>
            </dl>
            <p class="contact">変更をご希望される場合には下記までご連絡下さい。</p>
            <div class="email">
                <span class="email-icon"><img src="{{ asset('assets/admin/img/email-icon.png') }}" alt="メールアイコン"></span>
                <a href="mailto:support@casmen.jp">support@casmen.jp</a>
            </div>
        </div>
        <div class="privacy-policy">
            <a href="#">個人情報の取り扱いについて</a>
        </div>
    </div>
</main>
@endsection
