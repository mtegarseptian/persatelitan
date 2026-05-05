@extends('layouts.app')

@section('title', $satellite->name)
@section('page-title', $satellite->name)
@section('page-subtitle', 'Detail Informasi Satelit')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: Detail --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="stat-card rounded-2xl p-6">
            <div class="flex items-start gap-5 mb-6">
                <div class="w-24 h-24 rounded-2xl overflow-hidden flex-shrink-0 bg-gradient-to-br from-blue-500/20 to-purple-500/20 flex items-center justify-center">
                    @if($satellite->image)
                    <img src="{{ $satellite->image_url }}" class="w-full h-full object-cover">
                    @else
                    <i class="fas fa-satellite text-blue-400 text-3xl"></i>
                    @endif
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-white">{{ $satellite->name }}</h2>
                    <p class="text-gray-400 mt-1">{{ $satellite->country }}</p>
                    <div class="flex gap-2 mt-3">
                        <span class="px-3 py-1 rounded-lg text-xs font-medium badge-{{ $satellite->orbit_type }}">{{ $satellite->orbit_type }}</span>
                        <span class="px-3 py-1 rounded-lg text-xs font-medium {{ $satellite->is_active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                            {{ $satellite->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 rounded-xl bg-white/5">
                    <p class="text-xs text-gray-500 mb-1">Tanggal Peluncuran</p>
                    <p class="text-sm font-medium text-white">{{ $satellite->launch_date ? $satellite->launch_date->format('d F Y') : 'Tidak diketahui' }}</p>
                </div>
                <div class="p-4 rounded-xl bg-white/5">
                    <p class="text-xs text-gray-500 mb-1">Ground Station</p>
                    <p class="text-sm font-medium text-white">{{ $satellite->groundStation?->name ?? 'Tidak terhubung' }}</p>
                </div>
            </div>

            @if($satellite->tle_line1 || $satellite->tle_line2)
            <div class="mt-4 p-4 rounded-xl bg-space-900">
                <p class="text-xs text-gray-500 mb-2 font-semibold uppercase tracking-wider">TLE Data</p>
                <code class="text-xs text-green-400 font-mono block">{{ $satellite->tle_line1 }}</code>
                <code class="text-xs text-green-400 font-mono block mt-1">{{ $satellite->tle_line2 }}</code>
            </div>
            @endif

            @if($satellite->description)
            <div class="mt-4">
                <p class="text-xs text-gray-500 mb-2 font-semibold uppercase tracking-wider">Deskripsi</p>
                <p class="text-sm text-gray-300 leading-relaxed">{{ $satellite->description }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Right: Actions + Ground Station --}}
    <div class="space-y-6">
        <div class="stat-card rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-white mb-4">Aksi</h3>
            <div class="space-y-2">
                <a href="{{ route('satellites.edit', $satellite) }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl bg-yellow-500/20 text-yellow-400 hover:bg-yellow-500/30 transition-colors text-sm font-medium">
                    <i class="fas fa-edit"></i>Edit Satelit
                </a>
                <form method="POST" action="{{ route('satellites.destroy', $satellite) }}" onsubmit="return confirm('Yakin hapus satelit ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors text-sm font-medium">
                        <i class="fas fa-trash-alt"></i>Hapus Satelit
                    </button>
                </form>
                <a href="{{ route('satellites.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl bg-white/10 text-gray-300 hover:bg-white/20 transition-colors text-sm font-medium">
                    <i class="fas fa-arrow-left"></i>Kembali
                </a>
            </div>
        </div>

        @if($satellite->groundStation)
        <div class="stat-card rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-white mb-4">Ground Station</h3>
            <div class="p-3 rounded-xl bg-white/5">
                <p class="font-medium text-white text-sm">{{ $satellite->groundStation->name }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $satellite->groundStation->location }}</p>
                <p class="text-xs text-gray-400">{{ $satellite->groundStation->country }}</p>
                <p class="text-xs text-gray-500 mt-2 font-mono">
                    {{ $satellite->groundStation->latitude }}°, {{ $satellite->groundStation->longitude }}°
                </p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection