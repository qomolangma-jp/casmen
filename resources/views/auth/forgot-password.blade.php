<!DOCTYPE html>
<html lang="ja">

<head>
    <x-google-tag />
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="{{ asset('assets/admin/css/admin_login.css') }}">
	<link rel="shortcut icon" href="{{ asset('assets/admin/img/favicon.ico') }}" type="image/x-icon">
	<title>CASMEN｜Email</title>
</head>

<body>
	<header>
		<div class="header-container">
			<img src="{{ asset('assets/admin/img/logo_casmen_blue.png') }}" alt="Casmenロゴ">
		</div>
	</header>
	<main>
		<div class="login-container">
			<h1>Email</h1>

            <!-- Session Status -->
            @if (session('status'))
                <div class="warning" style="background-color: #d4edda; color: #155724; border-color: #c3e6cb;">
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="warning">
                    <ul class="warning-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

			<form method="POST" action="{{ route('password.email') }}">
                @csrf

				<!-- 変更箇所　開始 -->

				<!-- パスワード再設定メールが届かない場合、以下のメッセージを表示 -->
				<!-- <div class="warning">
					<span>パスワード再設定に関するメールを送信</span>
					<ul class="warning-list">
						<li>※ ログイン可能な管理者権限を持つ担当者の場合、管理画面上でパスワードの再設定が可能です。</li>
						<li>※ メールが届かない場合は、ログイン可能な管理者権限を持つ担当者にパスワードの再設定をご依頼ください。</li>
					</ul>
				</div> -->

				<!-- 変更箇所　終了 -->

				<div class="form-item email">
					<span>
						<img src="{{ asset('assets/admin/img/email-icon.png') }}" alt="メールアイコン">
					</span>
					<input id="email" name="email" placeholder="メールアドレス" type="email" value="{{ old('email') }}" required autofocus>
				</div>

				<button id="reset-email-btn" type="submit" class="login-btn">パスワード再設定メールを送信</button>
			</form>
			<a href="{{ route('login') }}">ログインページに戻る</a>
		</div>
		<div class="privacy-policy">
			<a href="{{ route('company.policy') }}" target="_blank">個人情報の取り扱いについて</a>
		</div>
	</main>
	<footer>
		<div class="footer-container">
			<small>Copyright&copy;CASMEN All Rights Reserved.</small>
		</div>
	</footer>
</body>

</html>
