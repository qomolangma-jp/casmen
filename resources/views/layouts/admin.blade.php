<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=640px, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/iziModal.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/admin/img/favicon.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <title>@yield('title', 'CASMEN｜管理画面')</title>
    @include('components.google-tag')
    @stack('styles')
</head>
<body>
    <header>
        <div class="header-container">
            <div class="header-container-inner">
                <input id="hamburger" type="checkbox">
                <label for="hamburger" id="open">
                    <img src="{{ asset('assets/admin/img/menu.png') }}" alt="メニューバー">
                </label>
                <nav class="header-menu">
                    <ul class="header-menu-list">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">
                                <img src="{{ asset('assets/admin/img/house-icon.png') }}" alt="グレーの家アイコン" class="default-icon">
                                <img src="{{ asset('assets/admin/img/hover-house-icon.png') }}" alt="青の家アイコン" class="hover-icon">
                                <span>ダッシュボード</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.notice.index') }}">
                                <img src="{{ asset('assets/admin/img/bell-icon.png') }}" alt="グレーのベルアイコン" class="default-icon">
                                <img src="{{ asset('assets/admin/img/hover-bell-icon.png') }}" alt="青のベルアイコン" class="hover-icon">
                                <span>お知らせ</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.link.create') }}">
                                <img src="{{ asset('assets/admin/img/people-icon.png') }}" alt="グレーの人アイコン" class="default-icon">
                                <img src="{{ asset('assets/admin/img/hover-people-icon.png') }}" alt="青の人アイコン" class="hover-icon">
                                <span>面接URL発行</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.entry.index') }}">
                                <img src="{{ asset('assets/admin/img/trans-icon.png') }}" alt="グレーの遷移アイコン" class="default-icon">
                                <img src="{{ asset('assets/admin/img/hover-trans-icon.png') }}" alt="青の遷移アイコン" class="hover-icon">
                                <span>応募者一覧</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.setting.index') }}">
                                <img src="{{ asset('assets/admin/img/setting-icon.png') }}" alt="グレーの歯車アイコン" class="default-icon">
                                <img src="{{ asset('assets/admin/img/hover-setting-icon.png') }}" alt="青の歯車アイコン" class="hover-icon">
                                <span>登録情報</span>
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
                                @csrf
                            </form>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <img src="{{ asset('assets/admin/img/exit-icon.png') }}" alt="グレーの出口アイコン" class="default-icon">
                                <img src="{{ asset('assets/admin/img/hover-exit-icon.png') }}" alt="青の出口アイコン" class="hover-icon">
                                <span>ログアウト</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <img src="{{ asset('assets/admin/img/logo_casmen_gray.png') }}" alt="Casmenロゴ">
                <span>{{ Auth::user()->name ?? '管理者' }}様</span>
            </div>
        </div>
    </header>

    @yield('content')

    <footer>
        <div class="footer-container">
            <small>Copyright&copy;CASMEN All Rights Reserved.</small>
        </div>
    </footer>

    <script src="{{ asset('assets/admin/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/iziModal.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/main.js') }}"></script>
    <script>
        // 二重送信防止
        $(document).on('submit', 'form', function() {
            $(this).find('button, input[type="submit"]').prop('disabled', true);
        });
    </script>
    @stack('scripts')
</body>
</html>
