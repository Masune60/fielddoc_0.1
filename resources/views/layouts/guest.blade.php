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
    <body class="font-sans text-gray-900 antialiased">
        <main class="auth-screen">
            <div class="auth-decoration" aria-hidden="true">
                <span class="auth-orbit auth-orbit-one"></span>
                <span class="auth-orbit auth-orbit-two"></span>
                <span class="auth-grid"></span>
                <div class="auth-visual-copy">
                    <span class="auth-visual-mark">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M5 4.75A1.75 1.75 0 0 1 6.75 3h10.5A1.75 1.75 0 0 1 19 4.75v14.5A1.75 1.75 0 0 1 17.25 21H6.75A1.75 1.75 0 0 1 5 19.25V4.75Z" stroke="currentColor" stroke-width="1.7"/><path d="M8.5 8h7M8.5 12h7m-7 4h4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                    </span>
                    <span>
                        <strong>FieldDoc</strong>
                        <small>Field operations</small>
                    </span>
                </div>
            </div>
            <div class="auth-card">
                <a href="/" class="auth-card-brand">
                    <x-application-logo class="h-9 w-9 fill-current" />
                </a>
                {{ $slot }}
            </div>
        </main>
    </body>
</html>
