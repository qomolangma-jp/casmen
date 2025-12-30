<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=640px, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/user/css/user.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/user/img/favicon.ico') }}" type="image/x-icon">
    <title>@yield('title', 'CASMEN｜らくらくセルフ面接')</title>
    @include('components.google-tag')
    @stack('styles')
</head>
<body>
    @yield('content')

    <script>
        // 二重送信防止（フォーム送信時）
        document.addEventListener('submit', function(e) {
            if (e.target.tagName === 'FORM') {
                const submitButtons = e.target.querySelectorAll('button, input[type="submit"]');
                submitButtons.forEach(button => {
                    button.disabled = true;
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
