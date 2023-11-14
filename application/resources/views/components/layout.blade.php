<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>
        @if(!empty($title))
            {{ $title }} |
        @endif
        {{ config('app.name', 'UK Poisons Information Database') }}
    </title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Scripts --}}
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body class="layout {{ str_contains($_SERVER['HTTP_USER_AGENT'], 'Electron') ? 'is-electron' : 'is-browser' }}">
<div class="layout__title-bar"></div>
<div class="layout__body">
    {{ $slot }}
</div>
</body>
</html>
