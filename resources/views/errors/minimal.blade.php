<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>
    <script>
        window.statusCode = @yield('code');
        window.message = "@yield('message')";
    </script>

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @viteReactRefresh
    @vite(['resources/frontend/main.tsx'])
</head>

<body>
    <div id="root"></div>


    <noscript class="antialiased">
        <div>
            @yield('code')
        </div>

        <div>
            @yield('message')
        </div>
    </noscript>
</body>

</html>
