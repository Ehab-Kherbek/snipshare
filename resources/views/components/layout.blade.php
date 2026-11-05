@props(['title' => 'SnipShare'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
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
