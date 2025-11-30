@extends('layouts.user')

@section('title', 'CASMEN｜らくらくセルフ面接')

@section('content')
<header>
    <div class="header-container">
        <div class="header-container-inner line-logo">
            <img src="{{ asset('assets/user/img/logo2.png') }}" alt="らくらくセルフ面接">
        </div>
    </div>
</header>
<main>
    <div class="main-container">
        <div class="main-content lg-content">
            <div class="lg-description">
                <h2>セルフ面接のやり方</h2>
                <dl class="interview-description">
                    <dt>カメラ・マイクをON</dt>
                    <dd>画面に出る許可ポップアップで「OK」をタップください。</dd>
                    <dt>録画ボタンスタート</dt>
                    <dd>セルフ面接スタートボタンをタップしてから、3秒後に質問が始ります。</dd>
                    <dt>質問は24問・約2分</dt>
                    <dd>1問につき約5秒。テンポよく表示される質問に、あなたのペースで答えてください。</dd>
                    <dt>やり直しは1回だけOK</dt>
                    <dd>「失敗した！」と思ったら、<br>もう一度だけ録画できます。</dd>
                    <dt>最後に確認して送信</dt>
                    <dd>確認画面で内容を見て<br>「送信」を押せば完了です。</dd>
                </dl>
            </div>
        </div>
        <a href="#" class="privacy-policy">個人情報の取り扱いについて</a>
        <a href="{{ route('record.interview-preview', ['token' => $token]) }}" class="purple-btn">個人情報に同意して次へ</a>
    </div>
</main>
<footer>
    <div class="footer-container">
        <p>ご不明点やトラブルがあれば、下記のサポートまでお気軽にご連絡ください。</p>
        <a href="mailto:support@casmen.jp">support@casmen.jp</a>
    </div>
</footer>
@endsection
