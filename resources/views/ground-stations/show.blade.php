@extends('layouts.app')

@section('title', $groundStation->name)
@section('page-title', $groundStation->name)
@section('page-subtitle', 'Detail Ground Station')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="stat-card rounded-2xl p-6">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 rounded-2xl bg-purple-500/20 flex items-center justify-center">
                    <i class="fas fa-broadcast-tower text-purple-400 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">{{ $groundStation->name }}</h2>
                    <p class="text-gray-400">{{ $groundStation->location }}, {{ $groundStation->country }}</p>
                    <span class="inline-flex items-center gap-1.5 mt-2 px-2.5 py-1 rounded-lg text-xs font-medium {{ $groundStation->is_active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                        {{ $groundStation->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="p-4 rounded-xl bg-white/5">
                    <p class="text-xs text-gray-500 mb-1">Latitude</p>
                    <p class="text-sm font-mono font-medium text-white">{{ $groundStation->latitude }}°</p>
                </div>
                <div class="p-4 rounded-xl bg-white/5">
                    <p class="text-xs text-gray-500 mb-1">Longitude</p>
                    <p class="text-sm font-mono font-medium text-white">{{ $groundStation->longitude }}°</p>
                </div>
                <div class="p-4 rounded-xl bg-white/5 col-span-2">
                    <p class="text-xs text-gray-500 mb-1">Total Satelit Terhubung</p>
                    <p class="text-2xl font-bold text-white">{{ $groundStation->satellites->count() }}</p>
                </div>
            </div>

            {{-- Map --}}
            <div id="detail-map" style="height: 300px;" class="rounded-xl overflow-hidden border border-white/10"></div>
        </div>

        {{-- Satellites --}}
        @if($groundStation->satellites->count() > 0)
        <div class="stat-card rounded-2xl p-6">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <i class="fas fa-satellite text-blue-400"></i>
                Satelit yang Dipantau
            </h3>
            <div class="space-y-3">
                @foreach($groundStation->satellites as $sat)
                <a href="{{ route('satellites.show', $sat) }}"
                   class="flex items-center gap-3 p-3 rounded-xl bg-white/5 hover:bg-white/10 transition-colors">
                    <div class="w-9 h-9 rounded-lg bg-blue-500/20 flex items-center justify-center">
                        <i class="fas fa-satellite text-blue-400 text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-white">{{ $sat->name }}</p>
                        <p class="text-xs text-gray-400">{{ $sat->country }} · {{ $sat->orbit_type }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-lg {{ $sat->is_active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                        {{ $sat->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="space-y-6">
        <div class="stat-card rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-white mb-4">Aksi</h3>
            <div class="space-y-2">
                <a href="{{ route('ground-stations.edit', $groundStation) }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl bg-yellow-500/20 text-yellow-400 hover:bg-yellow-500/30 transition-colors text-sm">
                    <i class="fas fa-edit"></i>Edit Stasiun
                </a>
                <form method="POST" action="{{ route('ground-stations.destroy', $groundStation) }}" onsubmit="return confirm('Hapus ground station ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors text-sm">
                        <i class="fas fa-trash-alt"></i>Hapus Stasiun
                    </button>
                </form>
                <a href="{{ route('ground-stations.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl bg-white/10 text-gray-300 hover:bg-white/20 transition-colors text-sm">
                    <i class="fas fa-arrow-left"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const map = L.map('detail-map').setView([{{ $groundStation->latitude }}, {{ $groundStation->longitude }}], 10);
L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    attribution: '©CartoDB', maxZoom: 19
}).addTo(map);
L.marker([{{ $groundStation->latitude }}, {{ $groundStation->longitude }}])
    .addTo(map)
    .bindPopup('<strong>{{ $groundStation->name }}</strong><br>{{ $groundStation->location }}')
    .openPopup();
</script>
@endpush