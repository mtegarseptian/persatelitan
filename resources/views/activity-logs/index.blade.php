@extends('layouts.app')

@section('title', 'Activity Log')
@section('page-title', 'Activity Log')
@section('page-subtitle', 'Riwayat semua aktivitas sistem')

@section('content')
<div class="stat-card rounded-2xl p-4 mb-4">
    <form method="GET" class="flex gap-3">
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="w-full bg-white/5 border border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-sm text-white focus:outline-none focus:border-blue-500"
                   placeholder="Cari aktivitas...">
        </div>
        <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm">Filter</button>
        <a href="{{ route('activity-logs.index') }}" class="px-5 py-2.5 bg-white/10 text-gray-300 rounded-xl text-sm">Reset</a>
    </form>
</div>

<div class="stat-card rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="data-table w-full">
            <thead>
                <tr class="border-b border-white/10">
                    <th class="text-left px-5 py-4 text-xs text-gray-400 uppercase">#</th>
                    <th class="text-left px-5 py-4 text-xs text-gray-400 uppercase">Deskripsi</th>
                    <th class="text-left px-5 py-4 text-xs text-gray-400 uppercase">Subject</th>
                    <th class="text-left px-5 py-4 text-xs text-gray-400 uppercase">User</th>
                    <th class="text-left px-5 py-4 text-xs text-gray-400 uppercase">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($activities as $activity)
                <tr class="transition-colors">
                    <td class="px-5 py-4 text-sm text-gray-400">{{ $activities->firstItem() + $loop->index }}</td>
                    <td class="px-5 py-4">
                        <p class="text-sm text-white">{{ $activity->description }}</p>
                    </td>
                    <td class="px-5 py-4 text-xs text-gray-400 font-mono">{{ $activity->subject_type ? class_basename($activity->subject_type) : '-' }}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-xs font-bold">
                                {{ $activity->causer ? strtoupper(substr($activity->causer->name, 0, 1)) : 'S' }}
                            </div>
                            <span class="text-sm text-gray-300">{{ $activity->causer?->name ?? 'System' }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-400">{{ $activity->created_at->format('d M Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-16 text-gray-500">
                        <i class="fas fa-history text-4xl mb-3 block opacity-30"></i>
                        Belum ada aktivitas tercatat
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($activities->hasPages())
    <div class="border-t border-white/10 px-5 py-4">
        {{ $activities->links('pagination::tailwind') }}
    </div>
    @endif
</div>
@endsection