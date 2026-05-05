@extends('layouts.app')

@section('title', 'Data Satelit')
@section('page-title', 'Data Satelit')
@section('page-subtitle', 'Kelola semua data satelit')

@section('content')
{{-- Toolbar --}}
<div class="stat-card rounded-2xl p-4 mb-4">
    <form method="GET" class="flex flex-col lg:flex-row gap-3">
        {{-- Search --}}
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-gray-500 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-gray-500 focus:outline-none focus:border-blue-500 transition-colors"
                   placeholder="Cari nama satelit...">
        </div>

        {{-- Filter Negara --}}
        <select name="country" class="bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm text-slate-800 dark:text-gray-300 focus:outline-none focus:border-blue-500 transition-colors cursor-pointer">
            <option value="">Semua Negara</option>
            @foreach($countries as $country)
            <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
            @endforeach
        </select>

        {{-- Filter Orbit --}}
        <select name="orbit_type" class="bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm text-slate-800 dark:text-gray-300 focus:outline-none focus:border-blue-500 transition-colors cursor-pointer">
            <option value="">Semua Orbit</option>
            @foreach($orbitTypes as $orbit)
            <option value="{{ $orbit }}" {{ request('orbit_type') == $orbit ? 'selected' : '' }}>{{ $orbit }}</option>
            @endforeach
        </select>

        {{-- Filter Status --}}
        <select name="status" class="bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm text-slate-800 dark:text-gray-300 focus:outline-none focus:border-blue-500 transition-colors cursor-pointer">
            <option value="">Semua Status</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
        </select>

        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors flex items-center justify-center">
            <i class="fas fa-filter mr-1"></i>Filter
        </button>
        <a href="{{ route('satellites.index') }}" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 dark:bg-white/10 dark:hover:bg-white/20 text-slate-700 dark:text-gray-300 rounded-xl text-sm font-medium transition-colors flex items-center justify-center">
            Reset
        </a>
    </form>
</div>

{{-- Action bar --}}
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-slate-600 dark:text-gray-400">Menampilkan <span class="text-slate-800 dark:text-white font-medium">{{ $satellites->count() }}</span> dari <span class="text-slate-800 dark:text-white font-medium">{{ $satellites->total() }}</span> satelit</p>

    <div class="flex gap-2">
        {{-- Export --}}
        <div class="relative group">
            <button class="flex items-center gap-2 px-4 py-2 bg-slate-200 hover:bg-slate-300 dark:bg-white/10 dark:hover:bg-white/20 text-slate-700 dark:text-gray-300 rounded-xl text-sm transition-colors">
                <i class="fas fa-download"></i>
                <span class="hidden sm:inline">Export</span>
                <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="absolute right-0 top-full mt-2 w-40 bg-white dark:bg-space-800 border border-slate-200 dark:border-white/10 rounded-xl shadow-2xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-10">
                <a href="{{ route('satellites.export.excel') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50 dark:hover:bg-white/10 text-sm text-slate-600 dark:text-gray-300 hover:text-slate-800 dark:hover:text-white transition-colors">
                    <i class="fas fa-file-excel text-green-500 dark:text-green-400"></i>Excel
                </a>
                <a href="{{ route('satellites.export.pdf') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50 dark:hover:bg-white/10 text-sm text-slate-600 dark:text-gray-300 hover:text-slate-800 dark:hover:text-white transition-colors">
                    <i class="fas fa-file-pdf text-red-500 dark:text-red-400"></i>PDF
                </a>
            </div>
        </div>

        {{-- Add button --}}
        <a href="{{ route('satellites.create') }}"
           class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl text-sm font-medium transition-all shadow-lg shadow-blue-500/30">
            <i class="fas fa-plus"></i>
            <span class="hidden sm:inline">Tambah Satelit</span>
        </a>
    </div>
</div>

{{-- Table --}}
<div class="stat-card rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table w-full">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/5 border-b border-slate-200 dark:border-white/10">
                    <th class="text-left px-5 py-4 text-xs font-semibold text-slate-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                    <th class="text-left px-5 py-4 text-xs font-semibold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Satelit</th>
                    <th class="text-left px-5 py-4 text-xs font-semibold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Negara</th>
                    <th class="text-left px-5 py-4 text-xs font-semibold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Orbit</th>
                    <th class="text-left px-5 py-4 text-xs font-semibold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Launch Date</th>
                    <th class="text-left px-5 py-4 text-xs font-semibold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Ground Station</th>
                    <th class="text-left px-5 py-4 text-xs font-semibold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="text-left px-5 py-4 text-xs font-semibold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($satellites as $satellite)
                <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-white/5">
                    <td class="px-5 py-4 text-sm text-slate-500 dark:text-gray-400">{{ $satellites->firstItem() + $loop->index }}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg overflow-hidden flex-shrink-0 bg-blue-100 dark:bg-white/5 flex items-center justify-center">
                                @if($satellite->image)
                                <img src="{{ $satellite->image_url }}" class="w-full h-full object-cover">
                                @else
                                <i class="fas fa-satellite text-blue-500 dark:text-blue-400 text-xs"></i>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-800 dark:text-white">{{ $satellite->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-gray-400">ID: {{ $satellite->id }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-sm text-slate-600 dark:text-gray-300">{{ $satellite->country }}</td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium badge-{{ $satellite->orbit_type }}">
                            {{ $satellite->orbit_type }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-sm text-slate-600 dark:text-gray-300">
                        {{ $satellite->launch_date ? $satellite->launch_date->format('d M Y') : '-' }}
                    </td>
                    <td class="px-5 py-4 text-sm text-slate-600 dark:text-gray-300">
                        {{ $satellite->groundStation?->name ?? '-' }}
                    </td>
                    <td class="px-5 py-4">
                        @if($satellite->is_active)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 dark:bg-green-400 animate-pulse"></span>Aktif
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 dark:bg-red-400"></span>Nonaktif
                        </span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('satellites.show', $satellite) }}"
                               class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 dark:bg-blue-500/20 dark:text-blue-400 dark:hover:bg-blue-500/30 transition-colors" title="Detail">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('satellites.edit', $satellite) }}"
                               class="w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-100 text-yellow-600 hover:bg-yellow-200 dark:bg-yellow-500/20 dark:text-yellow-400 dark:hover:bg-yellow-500/30 transition-colors" title="Edit">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <form method="POST" action="{{ route('satellites.destroy', $satellite) }}" onsubmit="return confirm('Hapus satelit ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-500/20 dark:text-red-400 dark:hover:bg-red-500/30 transition-colors" title="Hapus">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-16 text-slate-500 dark:text-gray-500">
                        <i class="fas fa-satellite text-4xl mb-3 block opacity-30"></i>
                        Belum ada data satelit
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($satellites->hasPages())
    <div class="border-t border-slate-200 dark:border-white/10 px-5 py-4">
        {{ $satellites->links('pagination::tailwind') }}
    </div>
    @endif
</div>
@endsection