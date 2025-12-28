<!DOCTYPE html>
<html lang="ja">

<head>
    <x-google-tag />
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="{{ asset('assets/company/css/company.css') }}">
	<link rel="shortcut icon" href="{{ asset('assets/company/img/favicon.ico') }}" type="image/x-icon">
	<title>CASMEN｜運営会社</title>
</head>

<body>
	<header>
		<div class="header-container">
			<img src="{{ asset('assets/company/img/logo-white.png') }}" alt="Casmenロゴ">
		</div>
	</header>
	<main>
		<div class="main-container">
			<h1>運営会社</h1>
			<dl class="company-info-list">
				<dt>販売業者</dt>
				<dd>株式会社WEBLA</dd>
				<dt>所 在 地</dt>
				<dd>
					〒101-0062<br>
					東京都千代田区神田駿河台2-11-16<br>
					さいかち坂ビル2階202<br>
					<small class="no-support">※対応サポートにつきましては別事業所にて行っております。<br>ご来社頂いても一切の対応を行う事が出来ませんのでご了承下さい。</small>
				</dd>
				<dt>連 絡 先</dt>
				<dd>
					support@casmen.jp
					<small class="no-phone-call">※電話によるお問い合わせは行っておりません。</small>
					<a href="mailto:support@casmen.jp" class="contact">各種お問い合わせはこちら</a>
				</dd>
			</dl>
		</div>
	</main>
	<footer>
		<div class="footer-container">
			<nav class="footer-menu">
				<ul class="footer-menu-list">
					<!-- 登録ページの入力画面に遷移 -->
					<li><a href="{{ route('register') }}">掲載の<br>お申込み</a></li>
					<li><a href="mailto:support@casmen.jp">お問い<br>合わせ</a></li>
					<li><a href="{{ route('company.terms') }}">利用規約</a></li>
					<li><a href="{{ route('company.policy') }}">プライバシー<br>ポリシー</a></li>
					<li><a href="{{ route('company.index') }}">運営会社</a></li>
				</ul>
			</nav>
			<div class="copyright">
				<small>&copy; 2025 casmen.jp</small>
			</div>
		</div>
	</footer>
</body>

</html>
