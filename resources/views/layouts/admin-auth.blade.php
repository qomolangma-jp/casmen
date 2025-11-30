<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/admin_login.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/admin/img/favicon.ico') }}" type="image/x-icon">
    <title>@yield('title', 'CASMEN｜管理画面')</title>
    @stack('styles')
</head>
<body>
    @yield('content')

    @stack('scripts')
</body>
</html>
