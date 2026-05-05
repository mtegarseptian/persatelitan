@extends('layouts.app')

@section('title', 'Tambah Satelit')
@section('page-title', 'Tambah Satelit')
@section('page-subtitle', 'Tambah data satelit baru ke sistem')

@section('content')
<div class="max-w-3xl">
    <div class="stat-card rounded-2xl p-6">
        <form method="POST" action="{{ route('satellites.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- Errors --}}
            @if($errors->any())
            <div class="p-4 rounded-xl bg-red-500/20 border border-red-500/30 text-red-400 text-sm">
                <p class="font-medium mb-1"><i class="fas fa-exclamation-circle mr-2"></i>Terdapat kesalahan:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Nama Satelit --}}
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-400 mb-2">Nama Satelit <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500 transition-colors"
                           placeholder="Contoh: Telkom-4">
                </div>

                {{-- Negara --}}
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Negara Pemilik <span class="text-red-400">*</span></label>
                    <input type="text" name="country" value="{{ old('country') }}" required
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500"
                           placeholder="Contoh: Indonesia">
                </div>

                {{-- Orbit Type --}}
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Orbit Type <span class="text-red-400">*</span></label>
                    <select name="orbit_type" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-gray-300 focus:outline-none focus:border-blue-500">
                        @foreach(['LEO', 'MEO', 'GEO', 'HEO'] as $orbit)
                        <option value="{{ $orbit }}" {{ old('orbit_type') == $orbit ? 'selected' : '' }}>{{ $orbit }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal Peluncuran --}}
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Tanggal Peluncuran</label>
                    <input type="date" name="launch_date" value="{{ old('launch_date') }}"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500">
                </div>

                {{-- Ground Station --}}
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Ground Station</label>
                    <select name="ground_station_id"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-gray-300 focus:outline-none focus:border-blue-500">
                        <option value="">-- Pilih Ground Station --</option>
                        @foreach($groundStations as $gs)
                        <option value="{{ $gs->id }}" {{ old('ground_station_id') == $gs->id ? 'selected' : '' }}>
                            {{ $gs->name }} ({{ $gs->country }})
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Status</label>
                    <label class="flex items-center gap-3 cursor-pointer p-3 bg-white/5 border border-white/10 rounded-xl">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                               class="w-5 h-5 rounded accent-blue-500">
                        <span class="text-sm text-gray-300">Satelit Aktif</span>
                    </label>
                </div>

                {{-- TLE Line 1 --}}
                <div>
                    <label class="block text-sm text-gray-400 mb-2">TLE Line 1</label>
                    <input type="text" name="tle_line1" value="{{ old('tle_line1') }}"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs font-mono text-white focus:outline-none focus:border-blue-500"
                           placeholder="1 25544U 98067A   ...">
                </div>

                {{-- TLE Line 2 --}}
                <div>
                    <label class="block text-sm text-gray-400 mb-2">TLE Line 2</label>
                    <input type="text" name="tle_line2" value="{{ old('tle_line2') }}"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs font-mono text-white focus:outline-none focus:border-blue-500"
                           placeholder="2 25544  51.6455 ...">
                </div>

                {{-- Image --}}
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-400 mb-2">Foto Satelit</label>
                    <div class="border-2 border-dashed border-white/20 rounded-xl p-6 text-center hover:border-blue-500/50 transition-colors cursor-pointer" onclick="document.getElementById('image').click()">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-600 mb-2"></i>
                        <p class="text-sm text-gray-400">Klik untuk upload foto satelit</p>
                        <p class="text-xs text-gray-600 mt-1">JPG, PNG, GIF — Maks 2MB</p>
                        <input type="file" name="image" id="image" class="hidden" accept="image/*" onchange="previewImage(this)">
                        <img id="image-preview" class="mt-3 mx-auto rounded-xl hidden max-h-32 object-contain">
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-400 mb-2">Deskripsi</label>
                    <textarea name="description" rows="4"
                              class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500"
                              placeholder="Deskripsi singkat tentang satelit ini...">{{ old('description') }}</textarea>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 pt-4 border-t border-white/10">
                <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl text-sm font-medium transition-all">
                    <i class="fas fa-save mr-2"></i>Simpan Satelit
                </button>
                <a href="{{ route('satellites.index') }}"
                   class="px-6 py-2.5 bg-white/10 hover:bg-white/20 text-gray-300 rounded-xl text-sm font-medium transition-colors">
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