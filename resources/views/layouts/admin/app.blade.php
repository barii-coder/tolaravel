<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8"/>
    <title>Chat UI - 4 Columns</title>
    <script src="https://cdn.tailwindcss.com"></script>

{{--        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))--}}
{{--        @vite(['resources/css/app.css', 'resources/js/app.js'])--}}
{{--    @endif--}}

    <title>{{ $title ?? 'Page Title' }}</title>
</head>
<body class="bg-gray-100 flex items-center justify-center">
    @yield('content')
</body>
</html>
