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
			<ul class="registration-flow-list thanks-flow">
				<li class="step"><span>1</span> 項目の入力</li>
				<li class="step"><span>2</span> 内容の確認</li>
				<li class="current-step"><span>3</span> 送信完了</li>
			</ul>
		</div>
		<div class="logo-container">
			<img src="{{ asset('assets/registration/img/thanks.png') }}" alt="＼ご登録ありがとうございます／">
		</div>
		<div class="main-container">
			<div class="cta-container">
				<span>すぐにCASMENで録画面接を開始！</span>
				<a href="{{ route('login') }}" class="cta-login-btn">管理画面にログイン</a>
				<p>ログイン後、<span class="create-url">「面接URL発行」</span>より<br>応募者の登録・URL発行を行ってください。</p>
			</div>
		</div>
		<div class="step-container">
			<div class="step-container-inner">
				<h2>録画面接の始め方（3ステップ）</h2>
				<ul class="step-list">
					<li>
						<img src="{{ asset('assets/registration/img/step-1.png') }}" alt="STEP1">
						<div class="how-to-use">
							<span class="to-do">応募者を登録<br>録画URLを発行・送信</span>
							<span class="screen">（店舗側画面）</span>
						</div>
						<img src="{{ asset('assets/registration/img/sm-1.png') }}" alt="スマホ">
						<p class="description">
							<!-- 擬似要素で● -->
							名前入力は必須。 メールアドレス<br>または電話番号を入力すると、録画<br>URLが自動送信。<br>
							<small>※入力しない場合は、発行されたURLをコピーしてLINE等で送信してください。</small>
						</p>
					</li>
					<li>
						<img src="{{ asset('assets/registration/img/step-2.png') }}" alt="STEP2">
						<div class="how-to-use">
							<span class="to-do">応募者が<br>録画面接を実施</span>
							<span class="screen">（応募者側画面）</span>
						</div>
						<img src="{{ asset('assets/registration/img/sm-2.png') }}" alt="スマホ">
						<p class="description">
							<!-- 擬似要素で● -->
							応募者がURLにアクセスし、質問に<br>回答しながら動画を撮影・送信。<br>
							<small>※アプリのインストールは不要です。</small>
						</p>
					</li>
					<li>
						<img src="{{ asset('assets/registration/img/step-3.png') }}" alt="STEP3">
						<div class="how-to-use">
							<span class="to-do">動画を確認<br>合否判定</span>
							<span class="screen">（店舗側画面）</span>
						</div>
						<img src="{{ asset('assets/registration/img/sm-3.png') }}" alt="スマホ">
						<p class="description">
							<!-- 擬似要素で● -->
							管理画面で動画を確認し、合否を<br>判定。<br>
							<small>※合格・不合格の通知メールを自動送信することも可能です。</small>
						</p>
					</li>
				</ul>
			</div>
		</div>
	</main>
    @include('parts.footer_sp')
</body>

</html>
