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
    <body class="font-sans antialiased">
        @if (request()->routeIs('dashboard'))
            {{ $slot }}
        @else
            <div class="app-shell" x-data="{ sidebarOpen: false }">
                <button
                    x-show="sidebarOpen"
                    x-transition.opacity
                    x-cloak
                    @click="sidebarOpen = false"
                    class="app-sidebar-backdrop"
                    aria-label="Tutup menu navigasi"
                ></button>
                @include('layouts.navigation')
                <div class="app-main">
                    <header class="app-topbar">
                        <div class="app-topbar-heading">
                            <button type="button" class="app-menu-button" @click="sidebarOpen = true" aria-label="Buka menu navigasi">
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                            </button>
                            @isset($header)
                                <div class="app-page-heading">{{ $header }}</div>
                            @endisset
                        </div>
                        <div class="app-topbar-profile">
                            <span class="app-avatar">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(Auth::user()->nama_lengkap, 0, 1)) }}</span>
                            <span class="app-topbar-user-copy">
                                <strong>{{ Auth::user()->nama_lengkap }}</strong>
                                <small>{{ Auth::user()->getRoleNames()->first() ?? 'Pengguna' }}</small>
                            </span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="app-logout" aria-label="Keluar" title="Keluar">
                                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M10 17l5-5-5-5m5 5H3m9-9h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </form>
                        </div>
                    </header>
                    <main class="app-content">{{ $slot }}</main>
                </div>
            </div>
        @endif
    </body>
</html>
