<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="module" src="https://cdn.jsdelivr.net/gh/starfederation/datastar@v1.0.0-beta.11/bundles/datastar.js"></script>


    <title>Price Tracker</title>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-400">

    @if($header ?? true === true)
        <header class="py-4 container border-slate-800 border-b-1 mb-8">
            <a href="/" class="font-bold">Price Tracker</a>
        </header>
    @endif

    <main class="container">
        @yield("main")
    </main>

</body>
</html>
