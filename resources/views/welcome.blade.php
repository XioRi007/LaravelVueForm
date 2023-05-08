<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel Form</title>
        <link rel="stylesheet" href="build/style.css">
{{--        @vite(['resources/js/app.js', 'resources/css/app.css'])--}}
    </head>
    <body class="antialiased">
        <!-- CSRF Token -->
        {{ csrf_field() }}
        <div id="app"></div>
        <script src="build/app.js"></script>
    </body>
</html>
