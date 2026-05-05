@extends('layouts.app')

@section('title', 'Edit Satelit')
@section('page-title', 'Edit Satelit')
@section('page-subtitle', 'Perbarui informasi satelit')

@section('content')
<div class="max-w-3xl">
    <div class="stat-card rounded-2xl p-6">
        <form method="POST" action="{{ route('satellites.update', $satellite) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Errors --}}
            @if($errors->any())
            <div class="p-4 rounded-xl bg-red-500/20 border border-red-500/30 text-red-400 text-sm">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-400 mb-2">Nama Satelit <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $satellite->name) }}" required
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Negara Pemilik <span class="text-red-400">*</span></label>
                    <input type="text" name="country" value="{{ old('country', $satellite->country) }}" required
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Orbit Type <span class="text-red-400">*</span></label>
                    <select name="orbit_type" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-gray-300 focus:outline-none focus:border-blue-500">
                        @foreach(['LEO', 'MEO', 'GEO', 'HEO'] as $orbit)
                        <option value="{{ $orbit }}" {{ old('orbit_type', $satellite->orbit_type) == $orbit ? 'selected' : '' }}>{{ $orbit }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Tanggal Peluncuran</label>
                    <input type="date" name="launch_date" value="{{ old('launch_date', $satellite->launch_date?->format('Y-m-d')) }}"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Ground Station</label>
                    <select name="ground_station_id"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-gray-300 focus:outline-none focus:border-blue-500">
                        <option value="">-- Pilih Ground Station --</option>
                        @foreach($groundStations as $gs)
                        <option value="{{ $gs->id }}" {{ old('ground_station_id', $satellite->ground_station_id) == $gs->id ? 'selected' : '' }}>
                            {{ $gs->name }} ({{ $gs->country }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Status</label>
                    <label class="flex items-center gap-3 cursor-pointer p-3 bg-white/5 border border-white/10 rounded-xl">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $satellite->is_active) ? 'checked' : '' }}
                               class="w-5 h-5 rounded accent-blue-500">
                        <span class="text-sm text-gray-300">Satelit Aktif</span>
                    </label>
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">TLE Line 1</label>
                    <input type="text" name="tle_line1" value="{{ old('tle_line1', $satellite->tle_line1) }}"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs font-mono text-white focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">TLE Line 2</label>
                    <input type="text" name="tle_line2" value="{{ old('tle_line2', $satellite->tle_line2) }}"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs font-mono text-white focus:outline-none focus:border-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-400 mb-2">Foto Satelit</label>
                    @if($satellite->image)
                    <div class="mb-3 flex items-center gap-3">
                        <img src="{{ $satellite->image_url }}" class="w-16 h-16 rounded-xl object-cover">
                        <p class="text-xs text-gray-400">Foto saat ini. Upload baru untuk mengganti.</p>
                    </div>
                    @endif
                    <div class="border-2 border-dashed border-white/20 rounded-xl p-6 text-center hover:border-blue-500/50 transition-colors cursor-pointer" onclick="document.getElementById('image').click()">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-600 mb-2"></i>
                        <p class="text-sm text-gray-400">Klik untuk upload foto baru</p>
                        <input type="file" name="image" id="image" class="hidden" accept="image/*" onchange="previewImage(this)">
                        <img id="image-preview" class="mt-3 mx-auto rounded-xl hidden max-h-32 object-contain">
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-400 mb-2">Deskripsi</label>
                    <textarea name="description" rows="4"
                              class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500">{{ old('description', $satellite->description) }}</textarea>
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-white/10">
                <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>Perbarui Satelit
                </button>
                <a href="{{ route('satellites.index') }}"
                   class="px-6 py-2.5 bg-white/10 text-gray-300 rounded-xl text-sm font-medium">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.classList.remove('hidden'); };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush