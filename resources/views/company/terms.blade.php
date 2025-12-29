<!DOCTYPE html>
<html lang="ja">

<head>
    <x-google-tag />
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="{{ asset('assets/company/css/company.css') }}">
	<link rel="shortcut icon" href="{{ asset('assets/company/img/favicon.ico') }}" type="image/x-icon">
	<title>CASMEN｜利用規約</title>
</head>

<body>
	<header>
		<div class="header-container">
			<img src="{{ asset('assets/company/img/logo-white.png') }}" alt="Casmenロゴ">
		</div>
	</header>
	<main>
		<div class="main-container">
			<h1>利用規約 </h1>
			<!-- 文言は仮 -->
			<!-- 仮のものとして、第3条まで記載しておきます -->
			<p>本利用規約（以下「本規約」といいます。）は、株式会社WEBLA（以下「当社」といいます。）が提供するセルフ録画面接サービス「CASMEN（キャスメン）」（以下「本サービス」といいます。）の利用条件を定めるものです。
本サービスを利用するすべての利用者は、本規約に同意した上で本サービスを利用するものとします。
			</p>
            <x-terms-content />
		</div>
	</main>
		</div>
	</main>
	<footer>
		<div class="footer-container">
			<nav class="footer-menu">
				<ul class="footer-menu-list">
					<!-- 登録ページの入力画面に遷移 -->
					<li><a href="{{ route('register') }}">掲載のお申込み</a></li>
					<li><a href="mailto:support@casmen.jp">お問い合わせ</a></li>
					<li><a href="{{ route('company.terms') }}">利用規約</a></li>
					<li><a href="{{ route('company.policy') }}">プライバシーポリシー</a></li>
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
