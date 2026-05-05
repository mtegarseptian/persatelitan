@extends('layouts.app')

@section('title', 'Activity Log')
@section('page-title', 'Activity Log')
@section('page-subtitle', 'Riwayat semua aktivitas sistem')

@section('content')
<div class="stat-card rounded-2xl p-4 mb-4">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-gray-500 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-gray-500 focus:outline-none focus:border-blue-500 transition-colors"
                   placeholder="Cari aktivitas...">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition-colors flex items-center justify-center">
                <i class="fas fa-filter mr-1"></i>Filter
            </button>
            <a href="{{ route('activity-logs.index') }}" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 dark:bg-white/10 dark:hover:bg-white/20 text-slate-700 dark:text-gray-300 rounded-xl text-sm font-medium transition-colors flex items-center justify-center">
                Reset
            </a>
        </div>
    </form>
</div>

<div class="stat-card rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table w-full">
            <thead>
                <tr class="bg-slate-50 dark:bg-white/5 border-b border-slate-200 dark:border-white/10">
                    <th class="text-left px-5 py-4 text-xs font-semibold text-slate-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                    <th class="text-left px-5 py-4 text-xs font-semibold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Deskripsi</th>
                    <th class="text-left px-5 py-4 text-xs font-semibold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Subject</th>
                    <th class="text-left px-5 py-4 text-xs font-semibold text-slate-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                    <th class="text-left px-5 py-4 text-xs font-semibold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($activities as $activity)
                <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-white/5">
                    <td class="px-5 py-4 text-sm text-slate-500 dark:text-gray-400">{{ $activities->firstItem() + $loop->index }}</td>
                    <td class="px-5 py-4">
                        <p class="text-sm font-medium text-slate-800 dark:text-white">{{ $activity->description }}</p>
                    </td>
                    <td class="px-5 py-4 text-xs font-mono text-slate-500 dark:text-gray-400">
                        {{ $activity->subject_type ? class_basename($activity->subject_type) : '-' }}
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-xs font-bold text-white shadow-sm">
                                {{ $activity->causer ? strtoupper(substr($activity->causer->name, 0, 1)) : 'S' }}
                            </div>
                            <span class="text-sm font-medium text-slate-700 dark:text-gray-300">
                                {{ $activity->causer?->name ?? 'System' }}
                            </span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-sm text-slate-500 dark:text-gray-400">
                        {{ $activity->created_at->format('d M Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-16 text-slate-500 dark:text-gray-500">
                        <i class="fas fa-history text-4xl mb-3 block opacity-30"></i>
                        Belum ada aktivitas tercatat
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($activities->hasPages())
    <div class="border-t border-slate-200 dark:border-white/10 px-5 py-4">
        {{ $activities->links('pagination::tailwind') }}
    </div>
    @endif
</div>
@endsection