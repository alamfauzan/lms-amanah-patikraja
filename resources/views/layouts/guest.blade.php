<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased" style="background-color: #f1f5f9;">
        <div class="min-h-screen flex flex-col justify-center items-center py-8 px-4">
            <div class="bg-white border border-gray-200 shadow-md rounded-2xl p-8 overflow-hidden" style="width: 100%; max-width: 480px;">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
