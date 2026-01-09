<!DOCTYPE html>
<html lang="ja">

<head>
    <x-google-tag />
	<meta charset="UTF-8">
	<meta name="viewport" content="width=640px, user-scalable=no">
	<link rel="stylesheet" href="{{ asset('assets/lp/css/lp.css') }}">
	<link rel="shortcut icon" href="{{ asset('assets/lp/img/favicon.ico') }}" type="image/x-icon">
	<title>“素顔を引き出す”セルフ録画面接サービス｜CASMEN</title>
</head>

<body>
	<!-- ここから追加 -->
	<header>
		<div class="header-container">
			<img src="{{ asset('assets/lp/img/logo.png') }}" alt="CASMENロゴ">
			<a href="{{ route('login') }}" target="_blank" rel="noopener noreferrer">利用登録がお済みの方<br>（ログインはこちら）</a>
		</div>
	</header>
	<!-- ここまで追加 -->
	<main>
		<div class="hero">
			<img src="{{ asset('assets/lp/img/main-visual-sm.png') }}" alt="もう履歴書にはダマされない!">
		</div>
		<div class="appeal">
			<div class="appeal-img-1">
				<img src="{{ asset('assets/lp/img/casmen-sm.png') }}" alt="売れるキャストを即座に見抜く録画面接サービス キャスメン CASMEN 遂に登場!">
			</div>
			<div class="video">
				<img src="{{ asset('assets/lp/img/sm-hand-sm.png') }}" alt="スマホ">
				<!-- ここに動画を入れてください -->
				<video src="{{ asset('assets/lp/video/casmen.mp4') }}" loop autoplay muted playsinline></video>
			</div>
			<div class="appeal-text">
				<img src="{{ asset('assets/lp/img/appeal-text-sm.png') }}" alt="累計78万人の応募データから導かれた”素顔を引き出す“セルフ録画面接サービス">
			</div>
			<div class="appeal-img-2">
				<img src="{{ asset('assets/lp/img/service-logo-sm.png') }}" alt="総合30億アクセス 求人応募数78万の実績">
			</div>
			<div class="recommend">
				<img src="{{ asset('assets/lp/img/recommend-sm.png') }}" alt="CASMENおすすめ業界">
			</div>
		</div>
		<div class="cta-top bg-purple">
			<div class="container cta-container">
				<img src="{{ asset('assets/lp/img/bubble-white.png') }}" alt="今だけ! 先着100店舗 完全無料">
				<a href="{{ route('register') }}" class="cta-btn">今すぐ始める</a>
				<a href="mailto:support@casmen.jp" class="contact-btn">お問い合わせはこちら＞</a>
			</div>
		</div>
		<div class="problem">
			<div class="container">
				<div class="problem-content">
					<img src="{{ asset('assets/lp/img/problem-sm.png') }}" alt="">
				</div>
				<div class="solution">
					<img src="{{ asset('assets/lp/img/solution-sm.png') }}" alt="＼その悩みをCASMENが解決／">
				</div>
			</div>
		</div>
		<div class="main-feature bg-purple">
			<div class="container">
				<img src="{{ asset('assets/lp/img/main-feature-sm.png') }}" alt="CASMENの主な機能">

				<!-- 紹介動画ができ次第、リンクを設置してボタンを表示させる -->
				<!-- <a href="#" class="introduction-mv">紹介動画</a> -->
			</div>
		</div>
		<div class="cta-middle-top bg-purple">
			<div class="container cta-container">
				<img src="{{ asset('assets/lp/img/bubble-white.png') }}" alt="今だけ! 先着100店舗 完全無料">
				<a href="{{ route('register') }}" class="cta-btn">今すぐ始める</a>
				<a href="mailto:support@casmen.jp" class="contact-btn">お問い合わせはこちら＞</a>
			</div>
		</div>
		<div class="reason">
			<img src="{{ asset('assets/lp/img/reason-sm.png') }}" alt="CASMENを使う3つの理由">
		</div>
		<div class="effect">
			<img src="{{ asset('assets/lp/img/effect-sm.png') }}" alt="CASMEN導入で得られる効果">
		</div>
		<div class="cta cta-middle-bottom">
			<div class="container cta-container">
				<img src="{{ asset('assets/lp/img/bubble-purple.png') }}" alt="今だけ! 先着100店舗 完全無料">
				<a href="{{ route('register') }}" class="cta-btn">今すぐ始める</a>
				<a href="mailto:support@casmen.jp" class="contact-btn">お問い合わせはこちら＞</a>
			</div>
		</div>
		<div class="flow bg-purple">
			<img src="{{ asset('assets/lp/img/flow-sm.png') }}" alt="ご利用の流れ">
		</div>
		<div class="faq bg-purple">
			<img src="{{ asset('assets/lp/img/faq-sm.png') }}" alt="よくある質問">
		</div>
		<div class="cta cta-bottom bg-purple">
			<div class="container cta-container">
				<img src="{{ asset('assets/lp/img/bubble-white.png') }}" alt="今だけ! 先着100店舗 完全無料">
				<a href="{{ route('register') }}" class="cta-btn">今すぐ始める</a>
				<a href="mailto:support@casmen.jp" class="contact-btn">お問い合わせはこちら＞</a>
			</div>
		</div>
	</main>
	<footer>
		<div class="footer-container">
			<div class="copyright">
				<small>&copy; 2025 casmen.jp</small>
			</div>
		</div>
	</footer>
</body>

</html>
