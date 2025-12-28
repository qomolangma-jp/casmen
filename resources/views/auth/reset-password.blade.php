<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="{{ asset('assets/admin/css/admin_login.css') }}">
	<link rel="shortcut icon" href="{{ asset('assets/admin/img/favicon.ico') }}" type="image/x-icon">
	<title>CASMEN｜新規パスワード</title>
</head>

<body>
	<header>
		<div class="header-container">
			<img src="{{ asset('assets/admin/img/logo_casmen_blue.png') }}" alt="Casmenロゴ">
		</div>
	</header>
	<main>
		<div class="login-container">
			<h1>新規パスワード</h1>
			<form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

				<!-- エラー表示 -->
                @if ($errors->any())
				<div class="warning">
					<span>エラー</span>
					<ul class="warning-list">
                        @foreach ($errors->all() as $error)
						<li>・{{ $error }}</li>
                        @endforeach
					</ul>
				</div>
                @endif

				<div class="form-item password">
					<span>
						<img src="{{ asset('assets/admin/img/password-icon.png') }}" alt="鍵アイコン">
					</span>
					<input id="password" name="password" placeholder="パスワード" type="password" required autocomplete="new-password">
					<div class="visible-icons">
						<img src="{{ asset('assets/admin/img/invisible-icon.png') }}" class="invisible" alt="非表示アイコン">
						<img src="{{ asset('assets/admin/img/visible-icon.png') }}" class="visible" alt="表示アイコン">
					</div>
				</div>
				<div class="form-item-wrap">
					<p>確認のためもう一度入力してください</p>
					<div class="form-item password">
						<span>
							<img src="{{ asset('assets/admin/img/password-icon.png') }}" alt="鍵アイコン">
						</span>
						<input id="password_confirmation" name="password_confirmation" placeholder="パスワード" type="password" required autocomplete="new-password">
						<div class="visible-icons">
							<img src="{{ asset('assets/admin/img/invisible-icon.png') }}" class="invisible" alt="非表示アイコン">
							<img src="{{ asset('assets/admin/img/visible-icon.png') }}" class="visible" alt="表示アイコン">
						</div>
					</div>
				</div>

				<button id="reset-email-btn" type="submit" class="login-btn">設定</button>
			</form>
			<a href="{{ route('login') }}">ログインページに戻る</a>
		</div>
		<div class="privacy-policy">
			<a href="#">個人情報の取り扱いについて</a>
		</div>
	</main>
	<footer>
		<div class="footer-container">
			<small>Copyright&copy;CASMEN All Rights Reserved.</small>
		</div>
	</footer>
	<!-- jQuery -->
	<script src="{{ asset('assets/admin/js/jquery-3.7.1.min.js') }}"></script>
	<!-- main.js -->
	<script src="{{ asset('assets/admin/js/main.js') }}"></script>
</body>

</html>
