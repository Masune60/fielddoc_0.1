<x-app-layout>
    <div class="dashboard" x-data="{ sidebarOpen: false }">
        <button
            x-show="sidebarOpen"
            x-transition.opacity
            x-cloak
            @click="sidebarOpen = false"
            class="dashboard-overlay"
            aria-label="Tutup menu navigasi"
        ></button>

        <aside class="dashboard-sidebar" :class="{ 'dashboard-sidebar-open': sidebarOpen }">
            <a href="{{ route('dashboard') }}" class="dashboard-brand">
                <span class="dashboard-brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M5 4.75A1.75 1.75 0 0 1 6.75 3h10.5A1.75 1.75 0 0 1 19 4.75v14.5A1.75 1.75 0 0 1 17.25 21H6.75A1.75 1.75 0 0 1 5 19.25V4.75Z" stroke="currentColor" stroke-width="1.7"/><path d="M8.5 8h7M8.5 12h7m-7 4h4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                </span>
                <span>
                    <strong>FieldDoc</strong>
                    <small>Field operations</small>
                </span>
                <button type="button" class="dashboard-sidebar-close" @click.prevent="sidebarOpen = false" aria-label="Tutup menu">
                    <svg viewBox="0 0 24 24" fill="none"><path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </button>
            </a>

            <div class="dashboard-nav-label">MENU UTAMA</div>
            <nav class="dashboard-nav" aria-label="Navigasi utama">
                <a href="{{ route('dashboard') }}" class="dashboard-nav-link is-active" aria-current="page">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 4h6v7H4V4Zm10 0h6v4h-6V4ZM14 12h6v8h-6v-8ZM4 15h6v5H4v-5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
                    <span>Dashboard</span>
                </a>
                @if ($canViewPk)
                    <a href="{{ route('pk.index') }}" class="dashboard-nav-link">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5v-9Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="m4.5 7.75 7.5 4.5 7.5-4.5M12 12.5V21" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
                        <span>Daftar PK / SPK</span>
                    </a>
                @endif
                @if ($canViewTahap)
                    <a href="{{ route('tahap.index') }}" class="dashboard-nav-link">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 3.75h7l5 5v11.5H7a2 2 0 0 1-2-2v-12.5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M14 4v5h5M9 13h6m-6 4h6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span>Tahap penagihan</span>
                        @if ($activeStages > 0)
                            <span class="dashboard-nav-count">{{ $activeStages }}</span>
                        @endif
                    </a>
                @endif
                @if ($canViewKhs)
                    <div class="dashboard-nav-label dashboard-nav-label-spaced">MASTER DATA</div>
                    <a href="{{ route('khs.index') }}" class="dashboard-nav-link">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21.5v-16Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M4 17.5A2.5 2.5 0 0 1 6.5 15H20M8 7h7" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                        <span>Master KHS</span>
                    </a>
                @endif
                @if ($canManageUsers)
                    <div class="dashboard-nav-label dashboard-nav-label-spaced">PENGATURAN</div>
                    <a href="{{ route('users.index') }}" class="dashboard-nav-link">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M16 20v-1.5a4.5 4.5 0 0 0-4.5-4.5h-4A4.5 4.5 0 0 0 3 18.5V20m15-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM9.5 10a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7Zm9 4a4.5 4.5 0 0 1 2.5 4v2" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                        <span>Manajemen pengguna</span>
                    </a>
                @endif
            </nav>

            <div class="dashboard-sidebar-bottom">
                <div class="dashboard-help-card">
                    <span class="dashboard-help-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M12 17v.01M9.1 9a3 3 0 1 1 5.2 2c-1.2 1-2.3 1.5-2.3 3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.7"/></svg>
                    </span>
                    <strong>Butuh bantuan?</strong>
                    <p>Kelola dokumentasi pekerjaan dalam satu tempat.</p>
                    @if ($canViewPk)
                        <a href="{{ route('pk.index') }}">Lihat pekerjaan <span aria-hidden="true">→</span></a>
                    @endif
                </div>
                <div class="dashboard-sidebar-user">
                    <span class="dashboard-avatar">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($user->nama_lengkap, 0, 1)) }}</span>
                    <span class="dashboard-sidebar-user-copy">
                        <strong>{{ $user->nama_lengkap }}</strong>
                        <small>{{ $user->getRoleNames()->first() ?? 'Pengguna' }}</small>
                    </span>
                    @if ($canManageProfile)
                        <a href="{{ route('profile.edit') }}" class="dashboard-user-settings" aria-label="Pengaturan profil">
                            <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.7"/><path d="m19.4 15 .1.1a1.7 1.7 0 1 1-2.4 2.4l-.1-.1a1.7 1.7 0 0 0-2.9 1.2v.2a1.7 1.7 0 1 1-3.4 0v-.2a1.7 1.7 0 0 0-2.9-1.2l-.1.1a1.7 1.7 0 1 1-2.4-2.4l.1-.1a1.7 1.7 0 0 0-1.2-2.9H4a1.7 1.7 0 1 1 0-3.4h.2a1.7 1.7 0 0 0 1.2-2.9l-.1-.1a1.7 1.7 0 1 1 2.4-2.4l.1.1a1.7 1.7 0 0 0 2.9-1.2V2a1.7 1.7 0 1 1 3.4 0v.2a1.7 1.7 0 0 0 2.9 1.2l.1-.1a1.7 1.7 0 1 1 2.4 2.4l-.1.1a1.7 1.7 0 0 0 1.2 2.9h.2a1.7 1.7 0 1 1 0 3.4h-.2a1.7 1.7 0 0 0-1.2 2.9Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>
                        </a>
                    @endif
                </div>
            </div>
        </aside>

        <div class="dashboard-main">
            <header class="dashboard-topbar">
                <div class="dashboard-topbar-left">
                    <button type="button" class="dashboard-menu-button" @click="sidebarOpen = true" aria-label="Buka menu navigasi">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </button>
                    <div class="dashboard-breadcrumb"><span>Workspace</span><span aria-hidden="true">/</span><strong>Dashboard</strong></div>
                </div>
                <div class="dashboard-topbar-right">
                    <span class="dashboard-system-status"><i></i> Sistem aktif</span>
                    <span class="dashboard-topbar-divider"></span>
                    <span class="dashboard-topbar-date">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</span>
                    <div class="dashboard-topbar-profile">
                        <span class="dashboard-avatar dashboard-avatar-small">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($user->nama_lengkap, 0, 1)) }}</span>
                        <span class="dashboard-topbar-user-copy"><strong>{{ $user->nama_lengkap }}</strong><small>{{ $user->getRoleNames()->first() ?? 'Pengguna' }}</small></span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dashboard-logout" title="Keluar" aria-label="Keluar">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M10 17l5-5-5-5m5 5H3m9-9h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </form>
                </div>
            </header>

            <main class="dashboard-content">
                <section class="dashboard-welcome">
                    <div>
                        <div class="dashboard-eyebrow"><span></span> RINGKASAN OPERASIONAL</div>
                        <h1>Selamat datang, {{ \Illuminate\Support\Str::before($user->nama_lengkap, ' ') }} <span aria-hidden="true">👋</span></h1>
                        <p>Pantau progres pekerjaan dan aktivitas terbaru hari ini.</p>
                    </div>
                    <div class="dashboard-welcome-actions">
                        @if ($canCreatePk)
                            <a href="{{ route('pk.create') }}" class="dashboard-button dashboard-button-primary">
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14m-7-7h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                                Buat PK baru
                            </a>
                        @endif
                        @if ($canManageTahap)
                            <a href="{{ route('tahap.create') }}" class="dashboard-button dashboard-button-secondary">Tambah tahap</a>
                        @endif
                        @if ($canManageKhs)
                            <a href="{{ route('khs.create') }}" class="dashboard-button dashboard-button-secondary">Tambah KHS</a>
                        @endif
                    </div>
                </section>

                <section class="dashboard-stats" aria-label="Ringkasan statistik">
                    @if ($canViewPk)
                        <article class="dashboard-stat-card">
                            <div class="dashboard-stat-top">
                                <span class="dashboard-stat-icon stat-indigo"><svg viewBox="0 0 24 24" fill="none"><path d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5v-9Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="m4.5 7.75 7.5 4.5 7.5-4.5M12 12.5V21" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg></span>
                                <span class="dashboard-stat-trend">Total data</span>
                            </div>
                            <strong class="dashboard-stat-value">{{ number_format($pkCount, 0, ',', '.') }}</strong>
                            <span class="dashboard-stat-label">Total PK / SPK terdaftar</span>
                            <a href="{{ route('pk.index') }}" class="dashboard-stat-link">Lihat semua <span aria-hidden="true">→</span></a>
                        </article>
                        <article class="dashboard-stat-card">
                            <div class="dashboard-stat-top">
                                <span class="dashboard-stat-icon stat-amber"><svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.7"/><path d="M12 7v5l3 2" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                                <span class="dashboard-stat-trend">Berjalan</span>
                            </div>
                            <strong class="dashboard-stat-value">{{ number_format($inProgressCount, 0, ',', '.') }}</strong>
                            <span class="dashboard-stat-label">Pekerjaan dalam proses</span>
                            <a href="{{ route('pk.index') }}" class="dashboard-stat-link">Pantau progres <span aria-hidden="true">→</span></a>
                        </article>
                        <article class="dashboard-stat-card">
                            <div class="dashboard-stat-top">
                                <span class="dashboard-stat-icon stat-green"><svg viewBox="0 0 24 24" fill="none"><path d="m5 12 4 4L19 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.7"/></svg></span>
                                <span class="dashboard-stat-trend">Tuntas</span>
                            </div>
                            <strong class="dashboard-stat-value">{{ number_format($completedCount, 0, ',', '.') }}</strong>
                            <span class="dashboard-stat-label">Pekerjaan selesai 100%</span>
                            <a href="{{ route('pk.index') }}" class="dashboard-stat-link">Lihat pekerjaan <span aria-hidden="true">→</span></a>
                        </article>
                    @endif
                    @if ($canViewPhotos)
                        <article class="dashboard-stat-card">
                            <div class="dashboard-stat-top">
                                <span class="dashboard-stat-icon stat-rose"><svg viewBox="0 0 24 24" fill="none"><path d="M12 8v4m0 4h.01M10.3 3.9 2.8 17a2 2 0 0 0 1.7 3h15a2 2 0 0 0 1.7-3l-7.5-13.1a2 2 0 0 0-3.4 0Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                                <span class="dashboard-stat-trend">Perlu tinjauan</span>
                            </div>
                            <strong class="dashboard-stat-value">{{ number_format($pendingPhotos, 0, ',', '.') }}</strong>
                            <span class="dashboard-stat-label">Foto menunggu verifikasi</span>
                            <a href="{{ route('pk.index') }}" class="dashboard-stat-link">Tinjau dokumentasi <span aria-hidden="true">→</span></a>
                        </article>
                    @elseif ($canViewTahap)
                        <article class="dashboard-stat-card">
                            <div class="dashboard-stat-top">
                                <span class="dashboard-stat-icon stat-blue"><svg viewBox="0 0 24 24" fill="none"><path d="M7 3.75h7l5 5v11.5H7a2 2 0 0 1-2-2v-12.5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M14 4v5h5M9 13h6m-6 4h6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg></span>
                                <span class="dashboard-stat-trend">Penagihan</span>
                            </div>
                            <strong class="dashboard-stat-value">{{ number_format($activeStages, 0, ',', '.') }}</strong>
                            <span class="dashboard-stat-label">Tahap belum dicairkan</span>
                            <a href="{{ route('tahap.index') }}" class="dashboard-stat-link">Lihat tahap <span aria-hidden="true">→</span></a>
                        </article>
                    @endif
                </section>

                <section class="dashboard-chart-grid" aria-label="Grafik aktivitas">
                    <article class="dashboard-panel dashboard-activity-panel">
                        <div class="dashboard-panel-heading">
                            <div>
                                <h2>Aktivitas pekerjaan</h2>
                                <p>Data baru dalam 7 hari terakhir</p>
                            </div>
                            <span class="dashboard-panel-period">
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3.5" y="5" width="17" height="16" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M16 3v4M8 3v4M3.5 10h17" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                                7 hari
                            </span>
                        </div>
                        @php($chartMax = max(1, $maxDailyActivity))
                        <div class="dashboard-bar-chart" role="img" aria-label="Grafik jumlah aktivitas per hari dalam tujuh hari terakhir">
                            @foreach ($dailyActivity as $point)
                                <div class="dashboard-bar-column" title="{{ $point['count'] }} aktivitas, {{ $point['date'] }}">
                                    <span class="dashboard-bar-value">{{ $point['count'] ?: '' }}</span>
                                    <div class="dashboard-bar-track">
                                        <span class="dashboard-bar" style="height: {{ $point['count'] ? max(10, ($point['count'] / $chartMax) * 100) : 4 }}%; opacity: {{ $point['count'] ? 1 : 0.25 }}"></span>
                                    </div>
                                    <span class="dashboard-bar-day">{{ $point['label'] }}</span>
                                    <span class="dashboard-bar-date">{{ $point['date'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </article>

                    <article class="dashboard-panel dashboard-distribution-panel">
                        <div class="dashboard-panel-heading">
                            <div>
                                <h2>{{ $distributionTitle }}</h2>
                                <p>Distribusi data saat ini</p>
                            </div>
                            <span class="dashboard-donut-menu" aria-hidden="true">•••</span>
                        </div>
                        <div class="dashboard-donut-layout">
                            <div class="dashboard-donut" style="background: {{ $distributionGradient }}" role="img" aria-label="Total {{ $distributionTotal }} data pada {{ $distributionTitle }}">
                                <div class="dashboard-donut-hole">
                                    <strong>{{ number_format($distributionTotal, 0, ',', '.') }}</strong>
                                    <span>Total data</span>
                                </div>
                            </div>
                            <ul class="dashboard-legend">
                                @forelse ($distribution as $item)
                                    <li>
                                        <span class="dashboard-legend-dot" style="background: {{ $item['color'] }}"></span>
                                        <span class="dashboard-legend-label">{{ $item['label'] }}</span>
                                        <strong>{{ number_format($item['value'], 0, ',', '.') }}</strong>
                                    </li>
                                @empty
                                    <li class="dashboard-legend-empty">Belum ada data untuk ditampilkan.</li>
                                @endforelse
                            </ul>
                        </div>
                    </article>
                </section>

                @if ($canViewTahap || $canViewPhotos)
                    <section class="dashboard-quick-stats" aria-label="Status proses">
                        <div class="dashboard-quick-heading">
                            <div><span class="dashboard-quick-heading-mark"></span><strong>Status proses</strong></div>
                            <span>Ringkasan status terbaru</span>
                        </div>
                        <div class="dashboard-quick-items">
                            @if ($canViewTahap)
                                <a href="{{ route('tahap.index') }}" class="dashboard-quick-item">
                                    <span class="dashboard-quick-indicator quick-amber"></span>
                                    <span class="dashboard-quick-copy"><strong>Draft</strong><small>Tahap penagihan</small></span>
                                    <strong class="dashboard-quick-count">{{ number_format($stageCounts['DRAFT'] ?? 0, 0, ',', '.') }}</strong>
                                    <span class="dashboard-quick-arrow" aria-hidden="true">→</span>
                                </a>
                                <a href="{{ route('tahap.index') }}" class="dashboard-quick-item">
                                    <span class="dashboard-quick-indicator quick-blue"></span>
                                    <span class="dashboard-quick-copy"><strong>Diajukan</strong><small>Tahap penagihan</small></span>
                                    <strong class="dashboard-quick-count">{{ number_format($stageCounts['DIAJUKAN'] ?? 0, 0, ',', '.') }}</strong>
                                    <span class="dashboard-quick-arrow" aria-hidden="true">→</span>
                                </a>
                                <a href="{{ route('tahap.index') }}" class="dashboard-quick-item">
                                    <span class="dashboard-quick-indicator quick-green"></span>
                                    <span class="dashboard-quick-copy"><strong>Dicairkan</strong><small>Tahap penagihan</small></span>
                                    <strong class="dashboard-quick-count">{{ number_format($stageCounts['DICAIRKAN'] ?? 0, 0, ',', '.') }}</strong>
                                    <span class="dashboard-quick-arrow" aria-hidden="true">→</span>
                                </a>
                            @endif
                            @if ($canViewPhotos)
                                <a href="{{ route('pk.index') }}" class="dashboard-quick-item">
                                    <span class="dashboard-quick-indicator quick-rose"></span>
                                    <span class="dashboard-quick-copy"><strong>Perlu diperbaiki</strong><small>Verifikasi foto</small></span>
                                    <strong class="dashboard-quick-count">{{ number_format($photoCounts['INVALID'] ?? 0, 0, ',', '.') }}</strong>
                                    <span class="dashboard-quick-arrow" aria-hidden="true">→</span>
                                </a>
                            @endif
                        </div>
                    </section>
                @endif

                <section class="dashboard-panel dashboard-recent-panel">
                    <div class="dashboard-panel-heading dashboard-recent-heading">
                        <div>
                            <h2>Aktivitas terbaru</h2>
                            <p>Data dan perubahan yang baru masuk ke sistem</p>
                        </div>
                        @if ($canViewPk)
                            <a href="{{ route('pk.index') }}" class="dashboard-view-all">Lihat semua <span aria-hidden="true">→</span></a>
                        @elseif ($canViewTahap)
                            <a href="{{ route('tahap.index') }}" class="dashboard-view-all">Lihat semua <span aria-hidden="true">→</span></a>
                        @endif
                    </div>
                    <div class="dashboard-table-wrap">
                        <table class="dashboard-table">
                            <thead>
                                <tr>
                                    <th scope="col">AKTIVITAS</th>
                                    <th scope="col">MODUL</th>
                                    <th scope="col">STATUS</th>
                                    <th scope="col">WAKTU</th>
                                    <th scope="col"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentActivity as $activity)
                                    <tr>
                                        <td>
                                            <div class="dashboard-activity-cell">
                                                <span class="dashboard-activity-icon">
                                                    @if ($activity['icon'] === 'image')
                                                        <svg viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="18" height="18" rx="3" stroke="currentColor" stroke-width="1.7"/><circle cx="8.5" cy="8.5" r="1.5" stroke="currentColor" stroke-width="1.5"/><path d="m21 15-5-5L5 21" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                    @elseif ($activity['icon'] === 'receipt')
                                                        <svg viewBox="0 0 24 24" fill="none"><path d="M7 3.75h7l5 5v11.5H7a2 2 0 0 1-2-2v-12.5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M14 4v5h5M9 13h6m-6 4h6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                                                    @else
                                                        <svg viewBox="0 0 24 24" fill="none"><path d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5v-9Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="m4.5 7.75 7.5 4.5 7.5-4.5M12 12.5V21" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
                                                    @endif
                                                </span>
                                                <span class="dashboard-activity-copy"><strong>{{ $activity['title'] }}</strong><small>{{ $activity['description'] }}</small></span>
                                            </div>
                                        </td>
                                        <td><span class="dashboard-module-label">{{ $activity['type'] }}</span></td>
                                        <td><span class="dashboard-status-badge status-{{ $activity['status_class'] }}"><i></i>{{ $activity['status'] }}</span></td>
                                        <td><span class="dashboard-time-label" title="{{ $activity['date_full'] }}">{{ $activity['date_label'] }}</span></td>
                                        <td><a href="{{ $activity['href'] }}" class="dashboard-row-action" aria-label="Buka {{ $activity['title'] }}">→</a></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="dashboard-empty-state">
                                            <span class="dashboard-empty-icon"><svg viewBox="0 0 24 24" fill="none"><path d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5v-9Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M8 12h8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg></span>
                                            <strong>Belum ada aktivitas</strong>
                                            <span>Aktivitas terbaru akan muncul di sini setelah ada data yang tercatat.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <footer class="dashboard-footer">
                    <span>FieldDoc <span aria-hidden="true">·</span> Sistem dokumentasi pekerjaan lapangan</span>
                    <span>Data diperbarui secara langsung</span>
                </footer>
            </main>
        </div>
    </div>
</x-app-layout>
