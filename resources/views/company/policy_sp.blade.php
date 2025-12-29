<!DOCTYPE html>
<html lang="ja">

<head>
    <x-google-tag />
	<meta charset="UTF-8">
	<meta name="viewport" content="width=640px, user-scalable=no">
	<link rel="stylesheet" href="{{ asset('assets/company/css/company.css') }}">
	<link rel="shortcut icon" href="{{ asset('assets/company/img/favicon.ico') }}" type="image/x-icon">
	<title>CASMEN｜プライバシーポリシー</title>
</head>

<body>
	<header>
		<div class="header-container">
			<img src="{{ asset('assets/registration/img/logo-white.png') }}" alt="Casmenロゴ">
		</div>
	</header>
	<main>
		<div class="main-container">
			<h1>プライバシーポリシー</h1>
<p class="policy-description">
				株式会社WEBLA（以下「当社」といいます。）は、当社が提供するサービス「CASMEN（キャスメン）」（以下「本サービス」といいます。）において取得する個人情報について、以下のとおりプライバシーポリシーを定め、適切な取り扱いと保護に努めます。
			</p>
			<dl class="privacy-policy-list">
				<dt>1. 取得する情報について</dt>
				<dd>
					当社は、本サービスの提供にあたり、以下の情報を取得することがあります。<br>
					（1）利用者から直接取得する情報
					<ul>
						<li>・氏名、ニックネーム</li>
						<li>・メールアドレス、その他連絡先情報</li>
						<li>・録画された動画・音声データ</li>
						<li>・利用時に入力されたその他の情報</li>
					</ul>
					（2）自動的に取得される情報
					<ul>
						<li>・IPアドレス</li>
						<li>・利用端末情報、ブラウザ情報</li>
						<li>・アクセス日時、操作履歴</li>
						<li>・クッキー（Cookie）等の識別情報</li>
					</ul>
				</dd>
				<dt>2. 利用目的</dt>
				<dd>
					取得した個人情報は、以下の目的の範囲内で利用します。
					<ul>
						<li>1.本サービスの提供および運営のため</li>
						<li>2.録画面接機能の提供および管理のため</li>
						<li>3.本人確認、不正利用防止のため</li>
						<li>4.利用状況の分析およびサービス改善のため</li>
						<li>5.お問い合わせへの対応のため</li>
						<li>6.法令または利用規約に基づく対応のため</li>
					</ul>
				</dd>
				<dt>3. 個人情報の第三者提供について</dt>
				<dd>
					当社は、次の場合を除き、取得した個人情報を第三者に提供しません。
					<ul>
						<li>・本人の同意がある場合</li>
						<li>・法令に基づく場合</li>
						<li>・人の生命・身体または財産の保護のために必要がある場合</li>
						<li>・業務委託先に対し、業務遂行上必要な範囲で提供する場合</li>
					</ul>
					※なお、応募者が提出した動画・情報は、当該応募に関わる企業ユーザーが閲覧できるものとします。
				</dd>
				<dt>4. 個人情報の管理</dt>
				<dd>
					当社は、個人情報への不正アクセス、紛失、漏えい、改ざん等を防止するため、合理的な安全対策を講じます。
				</dd>
				<dt>5. 動画データの保存期間について</dt>
				<dd>
					本サービスで取得した動画データは、アップロード日から30日経過後に自動的に削除されます。<br>
保存期間の延長を希望される場合は、別途当社が定める方法に従うものとします。
				</dd>
				<dt>6. 個人情報の開示・訂正・削除について</dt>
				<dd>
					本人から、自己の個人情報について開示・訂正・削除等の請求があった場合、当社は法令に基づき適切に対応いたします。
				</dd>
				<dt>7. プライバシーポリシーの変更</dt>
				<dd>
					当社は、法令の変更やサービス内容の変更等に応じて、本ポリシーを予告なく改定することがあります。<br>
変更後の内容は、本サービス上に掲載した時点で効力を生じます。
				</dd>
				<dt>8. お問い合わせ窓口</dt>
				<dd>
					本ポリシーに関するお問い合わせは、下記窓口までご連絡ください。<br>
					運営会社：株式会社WEBLA<br>
					お問い合わせ先：<a href="mailto:support@casmen.jp" class="contact">support@casmen.jp</a>
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
