<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Sistem Pengelolaan Limbah DTY</title>
    <script>
        try {
            if (localStorage.getItem('sistem-limbah-theme') === 'dark') {
                document.documentElement.classList.add('dark-mode');
            }
        } catch (error) {
            // Keep the default light theme if browser storage is unavailable.
        }
    </script>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0284c7',
                            600: '#0369a1',
                            700: '#075985',
                            800: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            transition: background-color .2s ease, color .2s ease;
        }

        .workspace-enter {
            animation: workspace-enter .45s cubic-bezier(.2, .7, .25, 1) both;
        }

        .role-nav-link {
            position: relative;
            transition: background-color .18s ease, color .18s ease, transform .18s ease, box-shadow .18s ease;
        }

        .role-nav-link:hover {
            transform: translateY(-1px);
        }

        .role-nav-link:focus-visible {
            outline: 2px solid #38bdf8;
            outline-offset: 2px;
        }

        .workspace-table th,
        .workspace-table td {
            padding: 1rem 1.25rem;
        }

        @media (max-width: 640px) {

            .workspace-table th,
            .workspace-table td {
                padding: .875rem 1rem;
            }
        }

        @keyframes workspace-enter {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }

            .role-nav-link:hover {
                transform: none;
            }
        }

        .operator-workspace {
            background-image: radial-gradient(ellipse at 10% 0%, rgba(14, 165, 233, .055), transparent 34%);
        }

        .admin-workspace {
            background-image: radial-gradient(ellipse at 92% 0%, rgba(16, 185, 129, .06), transparent 32%);
        }

        .manager-workspace {
            background-image: radial-gradient(ellipse at 8% 0%, rgba(139, 92, 246, .06), transparent 34%);
        }

        html.dark-mode {
            color-scheme: dark;
            background: #000;
        }

        html.dark-mode body {
            background-color: #000 !important;
            color: #e5e7eb;
        }

        html.dark-mode body.bg-slate-50 {
            background-color: #000 !important;
        }

        html.dark-mode body.operator-workspace {
            background-image: none;
        }

        html.dark-mode body.admin-workspace {
            background-image: none;
        }

        html.dark-mode body.manager-workspace {
            background-image: none;
        }

        html.dark-mode .bg-white,
        html.dark-mode [class~="bg-white/65"],
        html.dark-mode [class~="bg-white/95"],
        html.dark-mode [class~="bg-white/90"],
        html.dark-mode [class~="bg-white/80"],
        html.dark-mode [class~="bg-white/70"] {
            background-color: #111 !important;
            
        }

        html.dark-mode .bg-slate-50,
        html.dark-mode [class*="bg-slate-50/"],
        html.dark-mode .bg-gray-50 {
            background-color: #080808 !important;
        }

        html.dark-mode .bg-slate-100,
        html.dark-mode [class*="bg-slate-100/"],
        html.dark-mode .bg-gray-100,
        html.dark-mode .bg-slate-200,
        html.dark-mode [class*="bg-slate-200/"] {
            background-color: #191919 !important;
        }

        html.dark-mode [class*="bg-sky-50"],
        html.dark-mode [class*="bg-blue-50"] {
            background-color: #0c1a24 !important;
        }

        html.dark-mode [class*="bg-emerald-50"],
        html.dark-mode [class*="bg-green-50"] {
            background-color: #0b1d16 !important;
        }

        html.dark-mode [class*="bg-amber-50"],
        html.dark-mode [class*="bg-yellow-50"] {
            background-color: #211a0b !important;
        }

        html.dark-mode [class*="bg-rose-50"],
        html.dark-mode [class*="bg-red-50"] {
            background-color: #220f12 !important;
        }

        html.dark-mode [class*="bg-purple-50"],
        html.dark-mode [class*="bg-violet-50"] {
            background-color: #1a1227 !important;
        }

        html.dark-mode [class*="bg-sky-100"],
        html.dark-mode [class*="bg-blue-100"],
        html.dark-mode [class*="bg-emerald-100"],
        html.dark-mode [class*="bg-green-100"],
        html.dark-mode [class*="bg-amber-100"],
        html.dark-mode [class*="bg-yellow-100"],
        html.dark-mode [class*="bg-rose-100"],
        html.dark-mode [class*="bg-red-100"],
        html.dark-mode [class*="bg-purple-100"],
        html.dark-mode [class*="bg-violet-100"] {
            background-color: #202020 !important;
        }

        html.dark-mode [class~="text-slate-900"],
        html.dark-mode [class~="text-slate-800"],
        html.dark-mode [class~="text-gray-900"],
        html.dark-mode [class~="text-gray-800"],
        html.dark-mode [class~="text-black"] {
            color: #f4f4f5 !important;
        }

        html.dark-mode [class~="text-slate-700"],
        html.dark-mode [class~="text-slate-600"],
        html.dark-mode [class~="text-gray-700"],
        html.dark-mode [class~="text-gray-600"] {
            color: #d4d4d8 !important;
        }

        html.dark-mode [class~="text-slate-500"],
        html.dark-mode [class~="text-gray-500"] {
            color: #a1a1aa !important;
        }

        html.dark-mode [class~="text-slate-400"],
        html.dark-mode [class~="text-gray-400"] {
            color: #85858f !important;
        }

        html.dark-mode [class*="text-sky-700"],
        html.dark-mode [class*="text-sky-800"],
        html.dark-mode [class*="text-blue-700"],
        html.dark-mode [class*="text-blue-800"] {
            color: #7dd3fc !important;
        }

        html.dark-mode [class*="text-sky-600"],
        html.dark-mode [class*="text-blue-600"] {
            color: #38bdf8 !important;
        }

        html.dark-mode [class*="text-emerald-700"],
        html.dark-mode [class*="text-emerald-800"],
        html.dark-mode [class*="text-emerald-900"],
        html.dark-mode [class*="text-green-700"],
        html.dark-mode [class*="text-green-800"] {
            color: #6ee7b7 !important;
        }

        html.dark-mode [class*="text-emerald-600"],
        html.dark-mode [class*="text-green-600"] {
            color: #34d399 !important;
        }

        html.dark-mode [class*="text-amber-700"],
        html.dark-mode [class*="text-amber-800"],
        html.dark-mode [class*="text-amber-900"],
        html.dark-mode [class*="text-yellow-700"],
        html.dark-mode [class*="text-yellow-800"] {
            color: #fcd34d !important;
        }

        html.dark-mode [class*="text-amber-600"],
        html.dark-mode [class*="text-yellow-600"] {
            color: #fbbf24 !important;
        }

        html.dark-mode [class*="text-rose-700"],
        html.dark-mode [class*="text-rose-800"],
        html.dark-mode [class*="text-rose-900"],
        html.dark-mode [class*="text-red-700"],
        html.dark-mode [class*="text-red-800"] {
            color: #fda4af !important;
        }

        html.dark-mode [class*="text-rose-600"],
        html.dark-mode [class*="text-red-600"] {
            color: #fb7185 !important;
        }

        html.dark-mode [class*="text-purple-700"],
        html.dark-mode [class*="text-purple-800"],
        html.dark-mode [class*="text-violet-700"],
        html.dark-mode [class*="text-violet-800"] {
            color: #c4b5fd !important;
        }

        html.dark-mode [class*="text-purple-600"],
        html.dark-mode [class*="text-violet-600"] {
            color: #a78bfa !important;
        }

        html.dark-mode [class~="border-slate-100"],
        html.dark-mode [class~="border-slate-200"],
        html.dark-mode [class~="border-slate-300"],
        html.dark-mode [class~="border-gray-100"],
        html.dark-mode [class~="border-gray-200"],
        html.dark-mode [class~="border-gray-300"] {
            border-color: #303030 !important;
        }

        html.dark-mode input,
        html.dark-mode select,
        html.dark-mode textarea {
            background-color: #090909;
            border-color: #383838;
            color: #f4f4f5;
        }

        html.dark-mode input::placeholder,
        html.dark-mode textarea::placeholder {
            color: #85858f;
        }

        html.dark-mode option {
            background-color: #111;
            color: #f4f4f5;
        }

        html.dark-mode [class*="hover:bg-slate-"]:hover,
        html.dark-mode [class*="hover:bg-gray-"]:hover {
            background-color: #252525 !important;
        }

        html.dark-mode header,
        html.dark-mode footer {
            background-color: #050505 !important;
            border-color: #292929 !important;
        }

        html.dark-mode nav {
            background-color: #090909 !important;
            border-color: #292929 !important;
        }

        html.dark-mode table thead {
            background-color: #151515 !important;
        }

        html.dark-mode table tbody tr:hover {
            background-color: #171717 !important;
        }

        html.dark-mode #themeToggle {
            border-color: #333;
            background-color: #171717;
            color: #fcd34d;
        }

        html.dark-mode #themeToggle:hover {
            background-color: #252525;
        }

        html.dark-mode .shadow-sm,
        html.dark-mode .shadow-md,
        html.dark-mode .shadow-lg,
        html.dark-mode .shadow-xl {
            --tw-shadow-color: rgb(0 0 0 / 45%);
        }

        html.dark-mode aside {
            background-color: #0b0f19 !important;
            border-color: #1e2638 !important;
        }

        html.dark-mode aside .bg-white {
            background-color: #111625 !important;
            border-color: #1e2638 !important;
        }

        html.dark-mode aside .bg-slate-50\/50,
        html.dark-mode aside .bg-slate-100 {
            background-color: #161c2e !important;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body
    class="bg-slate-50/70 text-slate-800 antialiased min-h-screen @auth @if(Auth::user()->role === 'operator') operator-workspace @elseif(Auth::user()->role === 'admin') admin-workspace @elseif(Auth::user()->role === 'manajer') manager-workspace @endif @endauth">

    @auth
        <div class="min-h-screen flex">
            <!-- Sidebar Backdrop for mobile -->
            <div id="sidebarBackdrop"
                class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity"></div>

            <!-- Left Sidebar Navigation -->
            <aside id="appSidebar"
                class="fixed inset-y-0 left-0 z-50 w-72 bg-white/95 backdrop-blur-xl border-r border-slate-200/80 flex flex-col justify-between transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 lg:static lg:h-screen lg:sticky lg:top-0 no-print shadow-xl lg:shadow-none">
                <!-- Sidebar Top: Brand & Navigation -->
                <div class="flex flex-col flex-1 min-h-0">
                    <!-- Brand Header -->
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-sky-600 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-sky-500/20 shrink-0">
                                <i class="fa-solid fa-recycle text-lg"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-sm text-slate-900 tracking-tight leading-tight truncate">
                                    Sistem Limbah & Reject
                                </div>
                                <div class="text-[11px] text-slate-500 font-medium truncate mt-0.5">
                                    <strong>DTY</strong> (<em>Draw Textured Yarn</em>)
                                </div>
                            </div>
                        </div>
                        <!-- Close button on mobile -->
                        <button type="button" id="sidebarCloseBtn"
                            class="lg:hidden p-2 text-slate-400 hover:text-slate-700 rounded-lg"
                            aria-label="Tutup navigasi">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <!-- Workspace Role Pill -->
                    <div class="px-4 pt-4 pb-2">
                        @php
                            $roleConfig = match (Auth::user()->role) {
                                'operator' => [
                                    'bg' => 'bg-sky-50 text-sky-700 border-sky-150',
                                    'label' => 'Operator Produksi',
                                    'icon' => 'fa-helmet-safety',
                                    'activeClass' => 'bg-sky-600 text-white shadow-md shadow-sky-600/20 font-semibold',
                                    'inactiveClass' => 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80',
                                ],
                                'admin' => [
                                    'bg' => 'bg-emerald-50 text-emerald-700 border-emerald-150',
                                    'label' => 'Admin Produksi',
                                    'icon' => 'fa-shield-halved',
                                    'activeClass' => 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20 font-semibold',
                                    'inactiveClass' => 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80',
                                ],
                                'manajer' => [
                                    'bg' => 'bg-purple-50 text-purple-700 border-purple-150',
                                    'label' => 'Manajer Produksi',
                                    'icon' => 'fa-chart-line',
                                    'activeClass' => 'bg-purple-600 text-white shadow-md shadow-purple-600/20 font-semibold',
                                    'inactiveClass' => 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80',
                                ],
                                default => [
                                    'bg' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    'label' => ucfirst(Auth::user()->role),
                                    'icon' => 'fa-user',
                                    'activeClass' => 'bg-slate-900 text-white font-semibold',
                                    'inactiveClass' => 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80',
                                ]
                            };
                        @endphp
                        <div
                            class="flex items-center gap-2 px-3 py-2 rounded-xl border text-xs font-semibold {{ $roleConfig['bg'] }}">
                            <i class="fa-solid {{ $roleConfig['icon'] }} text-xs"></i>
                            <span>{{ $roleConfig['label'] }}</span>
                        </div>
                    </div>

                    <!-- Navigation Links List -->
                    <nav class="p-3 flex-1 overflow-y-auto">
                        <p class="px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Navigasi Utama
                        </p>
                        <div class="role-nav-track flex flex-col gap-1.5">
                            @if(Auth::user()->role === 'operator')
                                <a href="{{ route('operator.laporan.index') }}"
                                    class="role-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm transition-all {{ request()->routeIs('operator.laporan.index') ? $roleConfig['activeClass'] : $roleConfig['inactiveClass'] }}">
                                    <i class="fa-solid fa-clock-rotate-left w-4 text-center"></i>
                                    <span>Riwayat Laporan Shift</span>
                                </a>
                                <a href="{{ route('operator.laporan.create') }}"
                                    class="role-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm transition-all {{ request()->routeIs('operator.laporan.create', 'operator.laporan.edit') ? $roleConfig['activeClass'] : $roleConfig['inactiveClass'] }}">
                                    <i class="fa-solid fa-plus-circle w-4 text-center"></i>
                                    <span>Input Laporan Akhir Shift</span>
                                </a>
                            @elseif(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}"
                                    class="role-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm transition-all {{ request()->routeIs('admin.dashboard') ? $roleConfig['activeClass'] : $roleConfig['inactiveClass'] }}">
                                    <i class="fa-solid fa-gauge w-4 text-center"></i>
                                    <span>Dashboard Admin</span>
                                </a>
                                <a href="{{ route('admin.validasi.index') }}"
                                    class="role-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm transition-all {{ request()->routeIs('admin.validasi.*') ? $roleConfig['activeClass'] : $roleConfig['inactiveClass'] }}">
                                    <i class="fa-solid fa-clipboard-check w-4 text-center"></i>
                                    <span>Validasi & Verifikasi Laporan</span>
                                </a>
                                <a href="{{ route('admin.kategori.index') }}"
                                    class="role-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm transition-all {{ request()->routeIs('admin.kategori.*') ? $roleConfig['activeClass'] : $roleConfig['inactiveClass'] }}">
                                    <i class="fa-solid fa-tags w-4 text-center"></i>
                                    <span>Master Data Kategori Waste</span>
                                </a>
                            @elseif(Auth::user()->role === 'manajer')
                                <a href="{{ route('manajer.dashboard') }}"
                                    class="role-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm transition-all {{ request()->routeIs('manajer.dashboard') ? $roleConfig['activeClass'] : $roleConfig['inactiveClass'] }}">
                                    <i class="fa-solid fa-chart-line w-4 text-center"></i>
                                    <span>Executive Dashboard Analitik</span>
                                </a>
                                <a href="{{ route('manajer.laporan') }}"
                                    class="role-nav-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm transition-all {{ request()->routeIs('manajer.laporan') ? $roleConfig['activeClass'] : $roleConfig['inactiveClass'] }}">
                                    <i class="fa-solid fa-file-invoice w-4 text-center"></i>
                                    <span>Rekapitulasi Laporan Periodik</span>
                                </a>
                            @endif
                        </div>

                        <!-- Shortcut Standar Mutu DTY & Parameter Reject -->
                        <div class="mt-4 pt-3 border-t border-slate-100">
                            <p class="px-3 pb-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">Ketentuan Baku Mutu</p>
                            <button type="button" onclick="openStandarMutuModal()"
                                class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-sky-800 bg-sky-50/70 border border-sky-200/80 hover:bg-sky-100/80 transition text-left group">
                                <span class="flex items-center gap-2 truncate">
                                    <i class="fa-solid fa-award text-sky-600 group-hover:scale-110 transition-transform"></i>
                                    <span class="truncate">Standar Mutu DTY</span>
                                </span>
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-sky-200/80 text-sky-800 whitespace-nowrap">5.5 Kg AX</span>
                            </button>
                        </div>
                    </nav>
                </div>

                <!-- Sidebar Bottom: User Profile, Theme & Logout -->
                <div class="p-3 border-t border-slate-100 bg-slate-50/50">
                    <div
                        class="flex items-center justify-between p-2.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs mb-2">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-indigo-600 text-xs font-bold text-white shadow-xs">
                                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-slate-800 truncate leading-tight">{{ Auth::user()->name }}
                                </p>
                                <p class="text-[10px] text-slate-400 truncate mt-0.5">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Theme Toggle -->
                        <button type="button" id="themeToggle"
                            class="flex-1 inline-flex h-9 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white text-xs font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                            aria-label="Aktifkan mode gelap" aria-pressed="false" title="Aktifkan mode gelap">
                            <i id="themeToggleIcon" class="fa-solid fa-moon text-xs" aria-hidden="true"></i>
                            <span>Tema</span>
                        </button>

                        <!-- Logout Form -->
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?')"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-rose-200 bg-rose-50 text-rose-600 transition hover:bg-rose-100 hover:text-rose-700"
                                title="Keluar / Logout">
                                <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main Content Column Area -->
            <div class="flex-1 flex flex-col min-w-0 min-h-screen">
                <!-- Top Header on Main Area -->
                <header
                    class="h-16 border-b border-slate-200/80 bg-white/70 backdrop-blur-md px-4 sm:px-8 flex items-center justify-between sticky top-0 z-30 no-print">
                    <div class="flex items-center gap-3">
                        <!-- Mobile Hamburger -->
                        <button type="button" id="sidebarOpenBtn"
                            class="lg:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100"
                            aria-label="Buka navigasi">
                            <i class="fa-solid fa-bars text-lg"></i>
                        </button>
                        <div>
                            <span class="text-xs font-medium text-slate-400 hidden sm:inline">Sistem Pengelolaan Limbah DTY
                                &bull;</span>
                            <span class="text-xs sm:text-sm font-semibold text-slate-800">Politeknik Enjinering
                                Indorama</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="openStandarMutuModal()"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-sky-200 bg-sky-50/80 hover:bg-sky-100 text-sky-700 text-xs font-semibold transition"
                            title="Buka Ketentuan Standar Mutu Komoditas DTY & Parameter Reject">
                            <i class="fa-solid fa-award text-sky-600"></i>
                            <span class="hidden sm:inline">Standar Mutu DTY (AX 5.5 Kg)</span>
                            <span class="sm:hidden">Standar DTY</span>
                        </button>
                        <span
                            class="text-xs text-slate-500 font-medium hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 border border-slate-200/60">
                            <i class="fa-regular fa-calendar text-[11px]"></i>
                            {{ date('d M Y') }}
                        </span>
                    </div>
                </header>

                <!-- Main Content Area -->
                <main class="workspace-enter flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div
                            class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3 shadow-sm no-print">
                            <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 text-base"></i>
                            <div class="flex-1 text-sm font-medium leading-relaxed">{{ session('success') }}</div>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div
                            class="mb-5 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 flex items-start gap-3 shadow-sm no-print">
                            <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-0.5 text-base"></i>
                            <div class="flex-1 text-sm font-medium leading-relaxed">{{ session('warning') }}</div>
                        </div>
                    @endif

                    @if(session('info'))
                        <div
                            class="mb-5 p-4 rounded-xl bg-sky-50 border border-sky-200 text-sky-800 flex items-start gap-3 shadow-sm no-print">
                            <i class="fa-solid fa-circle-info text-sky-500 mt-0.5 text-base"></i>
                            <div class="flex-1 text-sm font-medium leading-relaxed">{{ session('info') }}</div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm no-print">
                            <div class="flex items-center gap-2 font-semibold text-sm mb-1.5">
                                <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                                <span>Terdapat beberapa kesalahan input:</span>
                            </div>
                            <ul class="list-disc list-inside text-sm space-y-1 text-rose-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </main>

                <!-- Footer -->
                <footer class="bg-white/80 border-t border-slate-200/80 py-4 text-center text-xs text-slate-500 no-print">
                    <p>&copy; 2026/2027 Proyek Manufaktur Tekstil • <strong>DTY</strong> (<em>Draw Textured Yarn</em>) •
                        Politeknik Enjinering Indorama</p>
                </footer>
            </div>
        </div>
    @else
        <!-- Guest Layout -->
        <div class="min-h-screen flex flex-col justify-between">
            <header class="bg-white/90 backdrop-blur-md border-b border-slate-200 p-4 flex items-center justify-between">
                <span class="font-bold text-sm text-slate-900">Sistem Limbah & Reject DTY</span>
                <button type="button" id="themeToggle" class="p-2 rounded-lg border border-slate-200 text-xs">
                    <i id="themeToggleIcon" class="fa-solid fa-moon"></i>
                </button>
            </header>
            <main class="workspace-enter flex-grow p-6">
                @yield('content')
            </main>
            <footer class="bg-white border-t border-slate-200 py-3 text-center text-xs text-slate-500">
                &copy; 2026/2027 Proyek Manufaktur Tekstil • <strong>DTY</strong> (<em>Draw Textured Yarn</em>)
            </footer>
        </div>
    @endauth

    @yield('scripts')
    <script>
            (function () {
                const toggle = document.getElementById('themeToggle');
                if (!toggle) return;

                const icon = document.getElementById('themeToggleIcon');
                const root = document.documentElement;

                function updateThemeControl() {
                    const isDark = root.classList.contains('dark-mode');
                    const label = isDark ? 'Aktifkan mode terang' : 'Aktifkan mode gelap';

                    toggle.setAttribute('aria-label', label);
                    toggle.setAttribute('aria-pressed', String(isDark));
                    toggle.setAttribute('title', label);
                    icon.className = isDark ? 'fa-solid fa-sun' : 'fa-solid fa-moon';

                    if (window.Chart) {
                        const color = isDark ? '#d4d4d8' : '#64748b';
                        const gridColor = isDark ? '#303030' : '#e2e8f0';
                        Chart.defaults.color = color;
                        Chart.defaults.borderColor = gridColor;

                        document.querySelectorAll('canvas').forEach(function (canvas) {
                            const chart = Chart.getChart(canvas);
                            if (!chart) return;

                            chart.options.color = color;
                            Object.values(chart.options.scales || {}).forEach(function (scale) {
                                if (scale.ticks) scale.ticks.color = color;
                                if (scale.grid) scale.grid.color = gridColor;
                            });

                            if (chart.options.plugins && chart.options.plugins.legend) {
                                chart.options.plugins.legend.labels = chart.options.plugins.legend.labels || {};
                                chart.options.plugins.legend.labels.color = color;
                            }

                            chart.update('none');
                        });
                    }
                }

                toggle.addEventListener('click', function () {
                    const isDark = root.classList.toggle('dark-mode');

                    try {
                        localStorage.setItem('sistem-limbah-theme', isDark ? 'dark' : 'light');
                    } catch (error) {
                        // Theme still applies for this page if browser storage is unavailable.
                    }

                    updateThemeControl();
                });

                updateThemeControl();

                // Mobile sidebar drawer
                const sidebar = document.getElementById('appSidebar');
                const backdrop = document.getElementById('sidebarBackdrop');
                const openBtn = document.getElementById('sidebarOpenBtn');
                const closeBtn = document.getElementById('sidebarCloseBtn');

                function openSidebar() {
                    if (sidebar) sidebar.classList.remove('-translate-x-full');
                    if (backdrop) backdrop.classList.remove('hidden');
                }

                function closeSidebar() {
                    if (sidebar) sidebar.classList.add('-translate-x-full');
                    if (backdrop) backdrop.classList.add('hidden');
                }

                if (openBtn) openBtn.addEventListener('click', openSidebar);
                if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
                if (backdrop) backdrop.addEventListener('click', closeSidebar);
            })();

            // Global modal controls for Standar Mutu Komoditas DTY
            function openStandarMutuModal() {
                const modal = document.getElementById('modalStandarMutu');
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.classList.add('overflow-hidden');
                }
            }

            function closeStandarMutuModal() {
                const modal = document.getElementById('modalStandarMutu');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.classList.remove('overflow-hidden');
                }
            }
    </script>

    <!-- Modal Standar Mutu Komoditas & Parameter Reject -->
    <div id="modalStandarMutu" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-xs overflow-y-auto p-4 sm:p-6 items-center justify-center">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all animate-in fade-in zoom-in-95">
            <!-- Header Modal -->
            <div class="relative bg-gradient-to-r from-slate-900 via-sky-950 to-indigo-950 p-6 text-white">
                <div class="flex items-start justify-between">
                    <div class="space-y-1">
                        <span class="inline-flex items-center gap-1.5 text-[10px] uppercase font-bold tracking-wider px-2.5 py-0.5 rounded-full bg-sky-400/20 text-sky-300 border border-sky-400/30">
                            <i class="fa-solid fa-certificate text-[9px]"></i> Ketentuan Baku Pabrik
                        </span>
                        <h3 class="text-xl font-extrabold text-white tracking-tight">Standar Mutu Komoditas & Parameter Reject</h3>
                        <p class="text-xs text-sky-200/80">Spesifikasi komoditas benang DTY (Draw Textured Yarn) &amp; ambang batas reject</p>
                    </div>
                    <button type="button" onclick="closeStandarMutuModal()" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-sm transition" title="Tutup">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body Content -->
            <div class="p-6 space-y-5 text-xs text-slate-600 max-h-[72vh] overflow-y-auto">
                <!-- 1. Klasifikasi Komoditas & Grade AX -->
                <div class="rounded-xl border border-emerald-200 bg-emerald-50/50 p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-base shrink-0">
                            <i class="fa-solid fa-award"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Mutu Produk Kualitas Tertinggi: Grade AX</h4>
                            <p class="text-slate-700 mt-1 leading-relaxed">
                                Mutu produk benang <strong>DTY</strong> (<em>Draw Textured Yarn</em>) kualitas tertinggi (<strong>Grade AX</strong>) memiliki standardisasi berat mutlak di angka <strong class="text-emerald-800 font-extrabold">5.5 Kg per gulungan (bobbin)</strong>. Ini adalah bobot baku standar yang menjadi batas minimum kelulusan mutu tertinggi komoditas.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 2. Aturan Bisnis Downgrade Otomatis -->
                <div class="rounded-xl border border-amber-200 bg-amber-50/50 p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-base shrink-0">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Aturan Bisnis: Penurunan Mutu (Downgrade) Otomatis</h4>
                            <p class="text-slate-700 mt-1 leading-relaxed">
                                Aturan bisnis pabrik menetapkan bahwa <strong>penurunan mutu (downgrade) diberlakukan otomatis</strong> jika bobot produk akhir per gulungan berada di bawah batas minimum AX (&lt; 5.5 Kg per bobbin). Gulungan benang yang mengalami downgrade ini diklasifikasikan sebagai <strong class="text-amber-900">Product Reject (seperti Grade B atau Grade C)</strong>.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 3. Alur Input Operator & Kalkulasi Reject Ratio -->
                <div class="rounded-xl border border-sky-200 bg-sky-50/50 p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center text-base shrink-0">
                            <i class="fa-solid fa-calculator"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Mekanisme Input &amp; Kalkulasi Reject Ratio</h4>
                            <p class="text-slate-700 mt-1 leading-relaxed">
                                Total akumulasi berat (Kg) dari seluruh gulungan benang reject / downgrade ini ditimbang dan diinput oleh <strong>Operator</strong> ke dalam sistem pada kategori <strong>Product Reject</strong>.
                            </p>
                            <div class="mt-2.5 p-3 rounded-lg bg-white border border-sky-200 font-mono text-[11px] text-sky-900">
                                <strong>Rumus Reject Ratio:</strong><br>
                                Reject Ratio (%) = [Total Waste &amp; Reject (kg) &divide; Total Output Produksi (kg)] &times; 100%
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1.5">
                                *Setiap penambahan bobot Product Reject akan langsung diakumulasikan ke total limbah dan meningkatkan persentase Reject Ratio shift terkait.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 4. Ringkasan Parameter Standar -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="p-3 rounded-xl border border-slate-200 bg-slate-50 text-center">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Standar Grade AX</span>
                        <span class="text-base font-extrabold text-emerald-700 block mt-1">5.5 Kg / Bobbin</span>
                        <span class="text-[10px] text-slate-500">Batas Bobot Mutlak</span>
                    </div>
                    <div class="p-3 rounded-xl border border-slate-200 bg-slate-50 text-center">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Kondisi Downgrade</span>
                        <span class="text-base font-extrabold text-amber-600 block mt-1">&lt; 5.5 Kg / Bobbin</span>
                        <span class="text-[10px] text-slate-500">Otomatis Product Reject</span>
                    </div>
                    <div class="p-3 rounded-xl border border-slate-200 bg-slate-50 text-center">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Klasifikasi Hasil</span>
                        <span class="text-base font-extrabold text-sky-700 block mt-1">Grade B / Grade C</span>
                        <span class="text-[10px] text-slate-500">Penambah Reject Ratio</span>
                    </div>
                </div>
            </div>

            <!-- Footer Modal -->
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[11px] text-slate-500">
                    <i class="fa-solid fa-circle-info text-sky-500 mr-1"></i> Standar acuan resmi operasional pabrik benang DTY.
                </span>
                <button type="button" onclick="closeStandarMutuModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs transition">
                    Tutup Panduan
                </button>
            </div>
        </div>
    </div>
</body>

</html>