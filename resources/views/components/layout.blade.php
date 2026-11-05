@props([
    'title' => 'SnipShare',
    'description' => 'Share code snippets with a short link. Paste your code, pick a language and send the link to anyone.',
    'noindex' => false,
])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">

    @if ($noindex)
        <meta name="robots" content="noindex">
    @endif

    <meta property="og:site_name" content="SnipShare">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('og-image.png') }}">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    {{ $head ?? '' }}
</head>
<body>
    <header class="navbar">
        <a href="{{ route('home') }}" class="brand">&lt;/&gt; SnipShare</a>
        <a href="{{ route('home') }}" class="btn btn-primary">+ New</a>
    </header>

    <main class="container">
        {{ $slot }}
    </main>
</body>
</html>
