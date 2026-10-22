<aside class="app-sidebar" :class="{ 'app-sidebar-open': sidebarOpen }">
    <a href="{{ route('dashboard') }}" class="app-brand">
        <span class="app-brand-mark" aria-hidden="true">
            <x-application-logo class="h-6 w-6 fill-current" />
        </span>
        <span>
            <strong>FieldDoc</strong>
            <small>Field operations</small>
        </span>
        <button type="button" class="app-sidebar-close" @click.prevent="sidebarOpen = false" aria-label="Tutup menu navigasi">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        </button>
    </a>

    <nav class="app-nav" aria-label="Navigasi utama">
        <span class="app-nav-label">MENU UTAMA</span>
        <a href="{{ route('dashboard') }}" class="app-nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}" @if (request()->routeIs('dashboard')) aria-current="page" @endif>
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 4h6v7H4V4Zm10 0h6v4h-6V4ZM14 12h6v8h-6v-8ZM4 15h6v5H4v-5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
            <span>Dasbor</span>
        </a>
        @can('view pk')
            <a href="{{ route('pk.index') }}" class="app-nav-link {{ request()->routeIs('pk.*') ? 'is-active' : '' }}" @if (request()->routeIs('pk.*')) aria-current="page" @endif>
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5v-9Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="m4.5 7.75 7.5 4.5 7.5-4.5M12 12.5V21" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
                <span>Daftar PK / SPK</span>
            </a>
        @endcan
        @can('view tahap')
            <a href="{{ route('tahap.index') }}" class="app-nav-link {{ request()->routeIs('tahap.*') ? 'is-active' : '' }}" @if (request()->routeIs('tahap.*')) aria-current="page" @endif>
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 3.75h7l5 5v11.5H7a2 2 0 0 1-2-2v-12.5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M14 4v5h5M9 13h6m-6 4h6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span>Tahap penagihan</span>
            </a>
        @endcan

        @can('view khs')
            <span class="app-nav-label app-nav-label-spaced">MASTER DATA</span>
            <a href="{{ route('khs.index') }}" class="app-nav-link {{ request()->routeIs('khs.*') ? 'is-active' : '' }}" @if (request()->routeIs('khs.*')) aria-current="page" @endif>
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21.5v-16Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M4 17.5A2.5 2.5 0 0 1 6.5 15H20M8 7h7" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                <span>Master KHS</span>
            </a>
        @endcan

        @can('manage users')
            <span class="app-nav-label app-nav-label-spaced">PENGATURAN</span>
            <a href="{{ route('users.index') }}" class="app-nav-link {{ request()->routeIs('users.*') ? 'is-active' : '' }}" @if (request()->routeIs('users.*')) aria-current="page" @endif>
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M16 20v-1.5a4.5 4.5 0 0 0-4.5-4.5h-4A4.5 4.5 0 0 0 3 18.5V20m15-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM9.5 10a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7Zm9 4a4.5 4.5 0 0 1 2.5 4v2" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                <span>Management User</span>
            </a>
        @endcan
    </nav>

    <div class="app-sidebar-user">
        <span class="app-avatar">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(Auth::user()->nama_lengkap, 0, 1)) }}</span>
        <span class="app-sidebar-user-copy">
            <strong>{{ Auth::user()->nama_lengkap }}</strong>
            <small>{{ Auth::user()->getRoleNames()->first() ?? 'Pengguna' }}</small>
        </span>
    </div>
</aside>
