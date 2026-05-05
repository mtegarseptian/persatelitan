@extends('layouts.app')

@section('title', 'Ground Station')
@section('page-title', 'Ground Station')
@section('page-subtitle', 'Kelola semua data stasiun bumi')

@section('content')
{{-- Toolbar --}}
<div class="stat-card rounded-2xl p-4 mb-4">
    <form method="GET" class="flex flex-col lg:flex-row gap-3">
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="w-full bg-white/5 border border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-blue-500"
                   placeholder="Cari stasiun atau negara...">
        </div>
        <select name="country" class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-gray-300 focus:outline-none focus:border-blue-500">
            <option value="">Semua Negara</option>
            @foreach($countries as $country)
            <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium">
            <i class="fas fa-filter mr-1"></i>Filter
        </button>
        <a href="{{ route('ground-stations.index') }}" class="px-5 py-2.5 bg-white/10 text-gray-300 rounded-xl text-sm font-medium">Reset</a>
    </form>
</div>

{{-- Action bar --}}
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-gray-400">Menampilkan <span class="text-white font-medium">{{ $groundStations->count() }}</span> dari <span class="text-white font-medium">{{ $groundStations->total() }}</span> ground station</p>
    <div class="flex gap-2">
        <div class="relative group">
            <button class="flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 text-gray-300 rounded-xl text-sm">
                <i class="fas fa-download"></i><span class="hidden sm:inline">Export</span><i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="absolute right-0 top-full mt-2 w-40 bg-space-800 border border-white/10 rounded-xl shadow-2xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-10">
                <a href="{{ route('ground-stations.export.excel') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-white/10 text-sm text-gray-300">
                    <i class="fas fa-file-excel text-green-400"></i>Excel
                </a>
                <a href="{{ route('ground-stations.export.pdf') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-white/10 text-sm text-gray-300">
                    <i class="fas fa-file-pdf text-red-400"></i>PDF
                </a>
            </div>
        </div>
        <a href="{{ route('ground-stations.create') }}"
           class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl text-sm font-medium">
            <i class="fas fa-plus"></i><span class="hidden sm:inline">Tambah Stasiun</span>
        </a>
    </div>
</div>

{{-- Map --}}
<div class="stat-card rounded-2xl overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-white/10">
        <h3 class="text-sm font-semibold text-white flex items-center gap-2">
            <i class="fas fa-map-marked-alt text-blue-400"></i>
            Peta Lokasi Ground Station
        </h3>
    </div>
    <div id="map" style="height: 350px;"></div>
</div>

{{-- Table --}}
<div class="stat-card rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table w-full">
            <thead>
                <tr class="border-b border-white/10">
                    <th class="text-left px-5 py-4 text-xs text-gray-400 uppercase tracking-wider">#</th>
                    <th class="text-left px-5 py-4 text-xs text-gray-400 uppercase tracking-wider">Nama Stasiun</th>
                    <th class="text-left px-5 py-4 text-xs text-gray-400 uppercase tracking-wider">Lokasi</th>
                    <th class="text-left px-5 py-4 text-xs text-gray-400 uppercase tracking-wider">Negara</th>
                    <th class="text-left px-5 py-4 text-xs text-gray-400 uppercase tracking-wider">Koordinat</th>
                    <th class="text-left px-5 py-4 text-xs text-gray-400 uppercase tracking-wider">Satelit</th>
                    <th class="text-left px-5 py-4 text-xs text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="text-left px-5 py-4 text-xs text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($groundStations as $gs)
                <tr class="transition-colors">
                    <td class="px-5 py-4 text-sm text-gray-400">{{ $groundStations->firstItem() + $loop->index }}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-purple-500/20 flex items-center justify-center">
                                <i class="fas fa-broadcast-tower text-purple-400 text-sm"></i>
                            </div>
                            <p class="text-sm font-medium text-white">{{ $gs->name }}</p>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-300">{{ $gs->location }}</td>
                    <td class="px-5 py-4 text-sm text-gray-300">{{ $gs->country }}</td>
                    <td class="px-5 py-4">
                        <p class="text-xs font-mono text-gray-400">{{ number_format($gs->latitude, 4) }}°</p>
                        <p class="text-xs font-mono text-gray-400">{{ number_format($gs->longitude, 4) }}°</p>
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-500/20 text-blue-400">
                            {{ $gs->satellites_count }} satelit
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        @if($gs->is_active)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-green-500/20 text-green-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>Aktif
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-red-500/20 text-red-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>Nonaktif
                        </span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('ground-stations.show', $gs) }}"
                               class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 transition-colors">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('ground-stations.edit', $gs) }}"
                               class="w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-500/20 text-yellow-400 hover:bg-yellow-500/30 transition-colors">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <form method="POST" action="{{ route('ground-stations.destroy', $gs) }}" onsubmit="return confirm('Hapus ground station ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-16 text-gray-500">
                        <i class="fas fa-broadcast-tower text-4xl mb-3 block opacity-30"></i>
                        Belum ada data ground station
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($groundStations->hasPages())
    <div class="border-t border-white/10 px-5 py-4">
        {{ $groundStations->links('pagination::tailwind') }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Initialize Leaflet Map
const map = L.map('map', { preferCanvas: true }).setView([0, 0], 2);

L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    attribution: '©OpenStreetMap ©CartoDB',
    maxZoom: 19
}).addTo(map);

// Custom marker icon
const stationIcon = L.divIcon({
    html: '<div style="background: rgba(124,58,237,0.9); width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid rgba(167,139,250,0.8); box-shadow: 0 0 15px rgba(124,58,237,0.5)"><i class="fas fa-broadcast-tower" style="color: white; font-size: 12px;"></i></div>',
    className: '',
    iconSize: [30, 30],
    iconAnchor: [15, 15],
});

// Ground station data
const stations = @json($groundStations->items());

stations.forEach(station => {
    if (station.latitude && station.longitude) {
        const marker = L.marker([station.latitude, station.longitude], { icon: stationIcon }).addTo(map);
        marker.bindPopup(`
            <div style="font-family: Inter, sans-serif; min-width: 150px;">
                <strong style="font-size: 13px;">${station.name}</strong><br>
                <span style="font-size: 11px; color: #666;">${station.location}, ${station.country}</span><br>
                <span style="font-size: 11px; color: #666;">${station.satellites_count || 0} satelit terhubung</span>
            </div>
        `);
    }
});
</script>
@endpush