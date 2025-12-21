<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=640px, user-scalable=no">
	<link rel="stylesheet" href="{{ asset('assets/registration/css/registration.css') }}">
	<link rel="shortcut icon" href="{{ asset('assets/registration/img/favicon.ico') }}" type="image/x-icon">
	<title>CASMEN｜利用登録(無料)</title>
</head>

<body>
	<header>
		<div class="header-container">
			<img src="{{ asset('assets/registration/img/logo-white.png') }}" alt="Casmenロゴ">
		</div>
	</header>
	<main>
		<div class="main-container">
			<h1>利用登録(無料)</h1>
			<ul class="registration-flow-list">
				<li class="step"><span>1</span> 項目の入力</li>
				<li class="current-step"><span>2</span> 内容の確認</li>
				<li class="step"><span>3</span> 送信完了</li>
			</ul>
			<p class="required-confirm">まだ、お問い合わせの送信は完了していません。<br>入力内容を確認して送信ボタンを押して下さい。</p>
			<form method="POST" action="{{ route('register') }}">
                @csrf
                <!-- Hidden fields to pass data -->
                <input type="hidden" name="email" value="{{ $data['email'] }}">
                <input type="hidden" name="shop_name" value="{{ $data['shop_name'] }}">
                <input type="hidden" name="shop_description" value="{{ $data['shop_description'] ?? '' }}">
                <input type="hidden" name="zip1" value="{{ $data['zip1'] ?? '' }}">
                <input type="hidden" name="zip2" value="{{ $data['zip2'] ?? '' }}">
                <input type="hidden" name="address" value="{{ $data['address'] ?? '' }}">
                <input type="hidden" name="tel" value="{{ $data['tel'] ?? '' }}">
                <input type="hidden" name="pic_name" value="{{ $data['pic_name'] ?? '' }}">

				<div class="contact-field confirm-contact-field">
					<h2>ご連絡先</h2>
					<dl class="info-list">
						<dt>メールアドレス</dt>
						<dd>{{ $data['email'] }}</dd>
						<dt>携帯電話番号</dt>
						<dd>{{ $data['tel'] ?? '' }}</dd>
					</dl>
				</div>
				<div class="shop-info-field">
					<h2>店舗情報</h2>
					<dl class="info-list">
						<dt>店舗名</dt>
						<dd>{{ $data['shop_name'] }}</dd>
						<dt>掲載URL</dt>
						<dd>{{ $data['shop_description'] ?? '' }}</dd>
					</dl>
				</div>
				<div class="mailing-address confirm-mailing-address">
					<h2>ご案内書類の郵送先</h2>
					<dl class="info-list">
						<dt>郵便番号</dt>
						<dd>{{ $data['zip1'] ?? '' }}-{{ $data['zip2'] ?? '' }}</dd>
						<dt>住所</dt>
						<dd>{{ $data['address'] ?? '' }}</dd>
						<dt>担当者名</dt>
						<dd>{{ $data['pic_name'] ?? '' }}</dd>
					</dl>
				</div>
				<div class="submit-btn-container send-btns">
					<button type="button" class="back-btn" onclick="history.back()">戻る</button>
					<button type="submit" class="submit-btn">送信する</button>
				</div>
			</form>
		</div>
	</main>
    @include('parts.footer_sp')
</body>

</html>
