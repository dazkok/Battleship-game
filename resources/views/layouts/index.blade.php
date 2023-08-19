<!DOCTYPE html>
<html lang="pl">

<head>
    <title>Battleship Game</title>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/x-icon" href="/images/logo/favicon.ico"/>
    <link rel="apple-touch-icon" href="/images/logo/favicon.ico">
    <meta name="description" content="Battleship Game">
    <meta name="keywords" content="Battleship, game">
    <meta name="author" content="Pavlo Vovk">
    <meta name="robots" content="noindex"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="theme-color" content="#fff">
    <link rel="manifest" href="{{ url('/') }}/manifest.json">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Battleship Game">
    <meta property="og:description" content="Battleship Game">
    <meta property="og:image" content="{{ url('/') }}/images/logo/logo.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="Battleship Game">
    <meta property="twitter:description" content="Battleship Game">
    <meta property="twitter:image" content="{{ url('/') }}/images/logo/logo.png">

    @include('layouts.headLinks')
    @yield('headSection')
</head>
<body>

@yield('content')


@include('layouts.footerLinks')

@yield('footerSection')

</body>
</html>
