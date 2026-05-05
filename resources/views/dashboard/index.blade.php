@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview Sistem Persatelitan')

@section('content')
{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    {{-- Total Satelit --}}
    <div class="stat-card rounded-2xl p-5 relative overflow-hidden" style="box-shadow: 0 0 20px rgba(59,130,246,0.15);">
        <div class="absolute top-0 right-0 w-20 h-20 bg-blue-500/10 rounded-full -mr-8 -mt-8"></div>
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs text-slate-500 dark:text-gray-400 uppercase tracking-wider">Total Satelit</p>
                <p class="text-3xl font-bold text-slate-800 dark:text-white mt-1">{{ $totalSatellites }}</p>
                <p class="text-xs text-blue-500 dark:text-blue-400 mt-1"><i class="fas fa-satellite"></i> Semua satelit</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                <i class="fas fa-satellite text-blue-500 dark:text-blue-400 text-xl"></i>
            </div>
        </div>
    </div>

    {{-- Satelit Aktif --}}
    <div class="stat-card rounded-2xl p-5 relative overflow-hidden" style="box-shadow: 0 0 20px rgba(16,185,129,0.15);">
        <div class="absolute top-0 right-0 w-20 h-20 bg-green-500/10 rounded-full -mr-8 -mt-8"></div>
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs text-slate-500 dark:text-gray-400 uppercase tracking-wider">Satelit Aktif</p>
                <p class="text-3xl font-bold text-slate-800 dark:text-white mt-1">{{ $activeSatellites }}</p>
                <p class="text-xs text-green-500 dark:text-green-400 mt-1"><i class="fas fa-circle"></i> Online</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center">
                <i class="fas fa-check-circle text-green-500 dark:text-green-400 text-xl"></i>
            </div>
        </div>
    </div>

    {{-- Satelit Nonaktif --}}
    <div class="stat-card rounded-2xl p-5 relative overflow-hidden" style="box-shadow: 0 0 20px rgba(239,68,68,0.15);">
        <div class="absolute top-0 right-0 w-20 h-20 bg-red-500/10 rounded-full -mr-8 -mt-8"></div>
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs text-slate-500 dark:text-gray-400 uppercase tracking-wider">Nonaktif</p>
                <p class="text-3xl font-bold text-slate-800 dark:text-white mt-1">{{ $inactiveSatellites }}</p>
                <p class="text-xs text-red-500 dark:text-red-400 mt-1"><i class="fas fa-circle"></i> Offline</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-red-500/20 flex items-center justify-center">
                <i class="fas fa-times-circle text-red-500 dark:text-red-400 text-xl"></i>
            </div>
        </div>
    </div>

    {{-- Ground Station --}}
    <div class="stat-card rounded-2xl p-5 relative overflow-hidden" style="box-shadow: 0 0 20px rgba(124,58,237,0.15);">
        <div class="absolute top-0 right-0 w-20 h-20 bg-purple-500/10 rounded-full -mr-8 -mt-8"></div>
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs text-slate-500 dark:text-gray-400 uppercase tracking-wider">Ground Station</p>
                <p class="text-3xl font-bold text-slate-800 dark:text-white mt-1">{{ $totalGroundStations }}</p>
                <p class="text-xs text-purple-500 dark:text-purple-400 mt-1"><i class="fas fa-broadcast-tower"></i> Stasiun</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center">
                <i class="fas fa-broadcast-tower text-purple-500 dark:text-purple-400 text-xl"></i>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Orbit Type Chart --}}
    <div class="stat-card rounded-2xl p-5">
        <h3 class="text-sm font-semibold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
            <i class="fas fa-chart-pie text-blue-500"></i> Distribusi Orbit
        </h3>
        <div class="relative h-[250px] w-full">
            <canvas id="orbitChart"></canvas>
        </div>
    </div>

    {{-- Country Chart --}}
    <div class="stat-card rounded-2xl p-5 lg:col-span-2">
        <h3 class="text-sm font-semibold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
            <i class="fas fa-chart-bar text-purple-500"></i> Top 10 Negara
        </h3>
        <div class="relative h-[250px] w-full">
            <canvas id="countryChart"></canvas>
        </div>
    </div>
</div>

{{-- Bottom Row: Satelit & Aktivitas Terbaru --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Recent Satellites --}}
    <div class="stat-card rounded-2xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-satellite text-blue-500 dark:text-blue-400"></i>
                Satelit Terbaru
            </h3>
            <a href="{{ route('satellites.index') }}" class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">Lihat semua →</a>
        </div>
        <div class="space-y-3">
            @forelse($recentSatellites as $satellite)
            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-white/5 dark:hover:bg-white/10 transition-colors border border-slate-100 dark:border-transparent">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500/20 to-purple-500/20 flex items-center justify-center flex-shrink-0">
                    @if($satellite->image)
                    <img src="{{ $satellite->image_url }}" class="w-full h-full rounded-lg object-cover">
                    @else
                    <i class="fas fa-satellite text-blue-600 dark:text-blue-400 text-sm"></i>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-800 dark:text-white truncate">{{ $satellite->name }}</p>
                    <p class="text-xs text-slate-500 dark:text-gray-400">{{ $satellite->country }} · {{ $satellite->orbit_type }}</p>
                </div>
                <span class="text-xs px-2 py-1 rounded-lg {{ $satellite->is_active ? 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400' }}">
                    {{ $satellite->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
            @empty
            <p class="text-center text-slate-500 dark:text-gray-500 py-8 text-sm">Belum ada data satelit</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="stat-card rounded-2xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-history text-green-500 dark:text-green-400"></i>
                Aktivitas Terbaru
            </h3>
            @if(auth()->check() && auth()->user()->isAdmin())
            <a href="{{ route('activity-logs.index') }}" class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">Lihat semua →</a>
            @endif
        </div>
        <div class="space-y-3">
            @forelse($recentActivities as $activity)
            <div class="flex items-start gap-3">
                <div class="w-2 h-2 rounded-full bg-blue-500 dark:bg-blue-400 mt-2 flex-shrink-0"></div>
                <div>
                    <p class="text-xs text-slate-700 dark:text-gray-300">{{ $activity->description }}</p>
                    <p class="text-xs text-slate-500 dark:text-gray-500 mt-1">
                        <i class="fas fa-user mr-1"></i>{{ $activity->causer?->name ?? 'System' }}
                        · {{ $activity->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
            @empty
            <p class="text-center text-slate-500 dark:text-gray-500 py-8 text-sm">Belum ada aktivitas</p>
            @endforelse
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const orbitData = @json($orbitStats);
const countryData = @json($countryStats);

// Orbit Doughnut Chart
const orbitCtx = document.getElementById('orbitChart').getContext('2d');
new Chart(orbitCtx, {
    type: 'doughnut',
    data: {
        labels: orbitData.map(d => d.orbit_type),
        datasets: [{
            data: orbitData.map(d => d.total),
            backgroundColor: ['#3b82f6', '#8b5cf6', '#10b981', '#f59e0b'],
            borderWidth: 0,
            hoverOffset: 8,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { color: '#6b7280', font: { size: 11 } } }
        },
        cutout: '60%',
    }
});

// Country Bar Chart
const countryCtx = document.getElementById('countryChart').getContext('2d');
new Chart(countryCtx, {
    type: 'bar',
    data: {
        labels: countryData.map(d => d.country),
        datasets: [{
            label: 'Jumlah Satelit',
            data: countryData.map(d => d.total),
            backgroundColor: 'rgba(59, 130, 246, 0.7)',
            borderColor: '#3b82f6',
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { color: '#6b7280', font: { size: 10 } }, grid: { display: false } },
            y: { ticks: { color: '#6b7280' }, grid: { color: 'rgba(107, 114, 128, 0.2)' } }
        }
    }
});
</script>
@endpush