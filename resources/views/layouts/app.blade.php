<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Persatelitan') — Sistem Manajemen Satelit</title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        space: {
                            900: '#0a0e1a',
                            800: '#0d1224',
                            700: '#111827',
                            600: '#1e2a45',
                            500: '#243252',
                            400: '#2d4070',
                        }
                    }
                }
            }
        }
    </script>

    {{-- Chart.js & Leaflet --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">

    {{-- WAJIB tambahkan type="text/tailwindcss" agar @apply terbaca CDN --}}
    <style type="text/tailwindcss">
        body { font-family: 'Inter', sans-serif; }
        .font-orbitron { font-family: 'Orbitron', monospace; }

        /* Sidebar Link Styling yang support Light/Dark */
        .sidebar-link {
            @apply flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 w-full text-slate-600 dark:text-gray-400;
        }
        .sidebar-link:hover {
            @apply bg-blue-50 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400;
        }
        .sidebar-link.active {
            @apply bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg;
        }

        /* Gradient card untuk Dark Mode, Solid Border untuk Light Mode */
        .stat-card {
            @apply bg-white dark:bg-transparent border border-slate-200 dark:border-white/10 shadow-sm dark:shadow-none transition-colors duration-300;
        }
        .dark .stat-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(10px);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { @apply bg-slate-100 dark:bg-space-800; }
        ::-webkit-scrollbar-thumb { @apply bg-blue-500 rounded-full; }
    </style>
</head>

<body class="bg-slate-50 dark:bg-space-900 text-slate-800 dark:text-gray-100 min-h-screen flex transition-colors duration-300">

    {{-- SIDEBAR --}}
    <aside id="sidebar" class="w-64 h-screen bg-white dark:bg-space-800 border-r border-slate-200 dark:border-white/10 flex flex-col fixed inset-y-0 left-0 z-50 hidden-mobile lg:translate-x-0 transition-transform duration-300">

        {{-- Logo --}}
        <div class="p-6 border-b border-slate-200 dark:border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <i class="fas fa-satellite text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="font-orbitron font-bold text-slate-800 dark:text-white text-sm leading-tight">PERSATELITAN</h1>
                    <p class="text-xs text-slate-500 dark:text-gray-400">Satellite Management</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        {{-- Penambahan flex flex-col agar menu pasti turun ke bawah --}}
        <nav class="flex-1 flex flex-col gap-1 p-4 overflow-y-auto">
            <p class="text-xs text-slate-400 dark:text-gray-500 uppercase tracking-widest px-4 py-2">Main Menu</p>

            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie w-5 text-center"></i>
                Dashboard
            </a>

            <a href="{{ route('satellites.index') }}" class="sidebar-link {{ request()->routeIs('satellites.*') ? 'active' : '' }}">
                <i class="fas fa-satellite w-5 text-center"></i>
                Data Satelit
            </a>

            <a href="{{ route('ground-stations.index') }}" class="sidebar-link {{ request()->routeIs('ground-stations.*') ? 'active' : '' }}">
                <i class="fas fa-broadcast-tower w-5 text-center"></i>
                Ground Station
            </a>

            @if(auth()->check() && auth()->user()->isAdmin())
            <div class="pt-2">
                <p class="text-xs text-slate-400 dark:text-gray-500 uppercase tracking-widest px-4 py-2">Admin</p>
                <a href="{{ route('activity-logs.index') }}" class="sidebar-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                    <i class="fas fa-history w-5 text-center"></i>
                    Activity Log
                </a>
            </div>
            @endif
        </nav>

        {{-- User info --}}
        @if(auth()->check())
        <div class="p-4 border-t border-slate-200 dark:border-white/10">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-100 dark:bg-white/5">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-xs font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-800 dark:text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500 dark:text-gray-400 capitalize">{{ auth()->user()->role }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-red-500 dark:text-gray-400 dark:hover:text-red-400 transition-colors" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
        @endif
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 flex flex-col min-h-screen lg:ml-64 overflow-x-hidden transition-all duration-300">

        {{-- Topbar --}}
        <header class="bg-white/80 dark:bg-space-800/80 backdrop-blur-sm border-b border-slate-200 dark:border-white/10 px-6 py-4 flex items-center justify-between sticky top-0 z-40 transition-colors duration-300">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle" class="lg:hidden text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h2 class="text-lg font-semibold text-slate-800 dark:text-white">@yield('page-title', 'Dashboard')</h2>
                    <p class="text-xs text-slate-500 dark:text-gray-400">@yield('page-subtitle', 'Sistem Manajemen Satelit')</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                {{-- Dark mode toggle --}}
                <button id="dark-mode-btn" class="w-9 h-9 rounded-lg bg-slate-100 dark:bg-white/10 hover:bg-slate-200 dark:hover:bg-white/20 flex items-center justify-center text-slate-600 dark:text-gray-400 hover:text-yellow-500 dark:hover:text-yellow-400 transition-all">
                    <i class="fas fa-sun" id="dark-icon"></i>
                </button>
                {{-- Current time --}}
                <div class="hidden sm:flex items-center gap-2 text-xs text-slate-600 dark:text-gray-400 bg-slate-100 dark:bg-white/5 px-3 py-2 rounded-lg">
                    <i class="fas fa-clock"></i>
                    <span id="current-time">{{ now()->format('H:i:s') }}</span>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <div class="flex-1 p-6 overflow-auto">
            @yield('content')
        </div>
    </main>

    <script>
        // Real-time Clock
        setInterval(() => {
            const now = new Date();
            const timeStr = String(now.getHours()).padStart(2,'0') + ':' + 
                            String(now.getMinutes()).padStart(2,'0') + ':' + 
                            String(now.getSeconds()).padStart(2,'0');
            document.getElementById('current-time').textContent = timeStr;
        }, 1000);

        // Sidebar Mobile
        document.getElementById('sidebar-toggle')?.addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('hidden-mobile');
        });

        // Fitur Dark/Light Mode yang Berfungsi Penuh
        const darkBtn = document.getElementById('dark-mode-btn');
        const darkIcon = document.getElementById('dark-icon');
        const html = document.documentElement;

        // Cek LocalStorage untuk mengingat tema terakhir
        if (localStorage.getItem('theme') === 'light') {
            html.classList.remove('dark');
            darkIcon.className = 'fas fa-moon';
        } else {
            html.classList.add('dark');
            darkIcon.className = 'fas fa-sun';
        }

        // Event Toggle
        darkBtn?.addEventListener('click', () => {
            html.classList.toggle('dark');
            const isDark = html.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            darkIcon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
        });
    </script>
    @stack('scripts')
</body>
</html>