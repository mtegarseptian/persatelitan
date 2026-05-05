<!DOCTYPE html>
<html lang="id" class="{{ session('theme', 'light') === 'dark' ? 'dark' : '' }}">
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
                        },
                        neon: {
                            blue: '#00d4ff',
                            purple: '#7c3aed',
                            green: '#10b981',
                        }
                    }
                }
            }
        }
    </script>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Leaflet Maps --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-orbitron { font-family: 'Orbitron', monospace; }

        /* Sidebar */
        .sidebar-link {
            @apply flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200;
        }
        .sidebar-link:hover {
            @apply bg-blue-500/20 text-blue-400;
        }
        .sidebar-link.active {
            @apply bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #1e2a45; }
        ::-webkit-scrollbar-thumb { background: #3b82f6; border-radius: 3px; }

        /* Glow effect */
        .glow-blue { box-shadow: 0 0 20px rgba(59, 130, 246, 0.3); }
        .glow-green { box-shadow: 0 0 20px rgba(16, 185, 129, 0.3); }
        .glow-red { box-shadow: 0 0 20px rgba(239, 68, 68, 0.3); }
        .glow-purple { box-shadow: 0 0 20px rgba(124, 58, 237, 0.3); }

        /* Star background animation */
        .stars-bg {
            background: radial-gradient(ellipse at bottom, #1B2735 0%, #090A0F 100%);
        }

        /* Gradient card */
        .stat-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
        }

        /* Table hover */
        .data-table tbody tr:hover {
            background: rgba(59, 130, 246, 0.08) !important;
        }

        /* Scrollable sidebar on mobile */
        #sidebar { transition: transform 0.3s ease; }
        @media (max-width: 1024px) {
            #sidebar.hidden-mobile { transform: translateX(-100%); }
        }

        /* Badge orbit types */
        .badge-LEO { @apply bg-blue-500/20 text-blue-400 border border-blue-500/30; }
        .badge-MEO { @apply bg-purple-500/20 text-purple-400 border border-purple-500/30; }
        .badge-GEO { @apply bg-green-500/20 text-green-400 border border-green-500/30; }
        .badge-HEO { @apply bg-orange-500/20 text-orange-400 border border-orange-500/30; }
    </style>
</head>

<body class="bg-space-900 dark:bg-space-900 text-gray-100 min-h-screen flex">

    {{-- SIDEBAR --}}
    <aside id="sidebar" class="w-64 min-h-screen bg-space-800 border-r border-white/10 flex flex-col fixed lg:relative z-50">

        {{-- Logo --}}
        <div class="p-6 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center glow-blue">
                    <i class="fas fa-satellite text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="font-orbitron font-bold text-white text-sm leading-tight">PERSATELITAN</h1>
                    <p class="text-xs text-gray-400">Satellite Management</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <p class="text-xs text-gray-500 uppercase tracking-widest px-4 py-2">Main Menu</p>

            <a href="{{ route('dashboard') }}"
               class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : 'text-gray-400' }}">
                <i class="fas fa-chart-pie w-5 text-center"></i>
                Dashboard
            </a>

            <a href="{{ route('satellites.index') }}"
               class="sidebar-link {{ request()->routeIs('satellites.*') ? 'active' : 'text-gray-400' }}">
                <i class="fas fa-satellite w-5 text-center"></i>
                Data Satelit
            </a>

            <a href="{{ route('ground-stations.index') }}"
               class="sidebar-link {{ request()->routeIs('ground-stations.*') ? 'active' : 'text-gray-400' }}">
                <i class="fas fa-broadcast-tower w-5 text-center"></i>
                Ground Station
            </a>

            @if(auth()->user()->isAdmin())
            <div class="pt-2">
                <p class="text-xs text-gray-500 uppercase tracking-widest px-4 py-2">Admin</p>
                <a href="{{ route('activity-logs.index') }}"
                   class="sidebar-link {{ request()->routeIs('activity-logs.*') ? 'active' : 'text-gray-400' }}">
                    <i class="fas fa-history w-5 text-center"></i>
                    Activity Log
                </a>
            </div>
            @endif
        </nav>

        {{-- User info --}}
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400 capitalize">{{ auth()->user()->role }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-400 transition-colors" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 flex flex-col min-h-screen overflow-hidden">

        {{-- Topbar --}}
        <header class="bg-space-800/80 backdrop-blur-sm border-b border-white/10 px-6 py-4 flex items-center justify-between sticky top-0 z-40">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle" class="lg:hidden text-gray-400 hover:text-white">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h2 class="text-lg font-semibold text-white">@yield('page-title', 'Dashboard')</h2>
                    <p class="text-xs text-gray-400">@yield('page-subtitle', 'Sistem Manajemen Satelit')</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                {{-- Dark mode toggle --}}
                <button id="dark-mode-btn" class="w-9 h-9 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center text-gray-400 hover:text-yellow-400 transition-all">
                    <i class="fas fa-moon" id="dark-icon"></i>
                </button>
                {{-- Current time --}}
                <div class="hidden sm:flex items-center gap-2 text-xs text-gray-400 bg-white/5 px-3 py-2 rounded-lg">
                    <i class="fas fa-clock"></i>
                    <span id="current-time">{{ now()->format('H:i:s') }}</span>
                </div>
            </div>
        </header>

        {{-- Alert Messages --}}
        @if(session('success'))
        <div class="mx-6 mt-4 flex items-center gap-3 bg-green-500/20 border border-green-500/30 text-green-400 px-4 py-3 rounded-xl" id="alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button onclick="document.getElementById('alert-success').remove()" class="ml-auto"><i class="fas fa-times"></i></button>
        </div>
        @endif

        @if(session('error'))
        <div class="mx-6 mt-4 flex items-center gap-3 bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl" id="alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
            <button onclick="document.getElementById('alert-error').remove()" class="ml-auto"><i class="fas fa-times"></i></button>
        </div>
        @endif

        {{-- Page Content --}}
        <div class="flex-1 p-6 overflow-auto">
            @yield('content')
        </div>

        {{-- Footer --}}
        <footer class="border-t border-white/10 px-6 py-3 text-center text-xs text-gray-500">
            © {{ date('Y') }} Persatelitan — Sistem Manajemen Satelit | Dibuat dengan ❤️ menggunakan Laravel
        </footer>
    </main>

    {{-- Sidebar overlay mobile --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

    <script>
        // Clock
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeEl = document.getElementById('current-time');
            if (timeEl) timeEl.textContent = `${hours}:${minutes}:${seconds}`;
        }
        setInterval(updateTime, 1000);

        // Sidebar toggle mobile
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('hidden-mobile');
                overlay.classList.toggle('hidden');
            });
            overlay.addEventListener('click', () => {
                sidebar.classList.add('hidden-mobile');
                overlay.classList.add('hidden');
            });
        }

        // Dark mode toggle (visual only, default is already dark)
        const darkBtn = document.getElementById('dark-mode-btn');
        const html = document.documentElement;
        if (darkBtn) {
            darkBtn.addEventListener('click', () => {
                html.classList.toggle('dark');
            });
        }

        // Auto dismiss alerts
        setTimeout(() => {
            ['alert-success', 'alert-error'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.remove();
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>