@extends('layouts.app')

@section('title', 'Tambah Ground Station')
@section('page-title', 'Tambah Ground Station')
@section('page-subtitle', 'Tambah data stasiun bumi baru')

@section('content')
<div class="max-w-3xl">
    <div class="stat-card rounded-2xl p-6">
        <form method="POST" action="{{ route('ground-stations.store') }}" class="space-y-5">
            @csrf

            @if($errors->any())
            <div class="p-4 rounded-xl bg-red-500/20 border border-red-500/30 text-red-400 text-sm">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-400 mb-2">Nama Stasiun <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500"
                           placeholder="Contoh: Stasiun Bumi Cibinong">
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Lokasi / Kota <span class="text-red-400">*</span></label>
                    <input type="text" name="location" value="{{ old('location') }}" required
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500"
                           placeholder="Contoh: Cibinong, Jawa Barat">
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Negara <span class="text-red-400">*</span></label>
                    <input type="text" name="country" value="{{ old('country') }}" required
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500"
                           placeholder="Contoh: Indonesia">
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Latitude <span class="text-red-400">*</span></label>
                    <input type="number" name="latitude" value="{{ old('latitude') }}" required step="any" min="-90" max="90"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500"
                           placeholder="-6.4930">
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Longitude <span class="text-red-400">*</span></label>
                    <input type="number" name="longitude" value="{{ old('longitude') }}" required step="any" min="-180" max="180"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500"
                           placeholder="106.8318">
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Status</label>
                    <label class="flex items-center gap-3 cursor-pointer p-3 bg-white/5 border border-white/10 rounded-xl">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 rounded accent-blue-500">
                        <span class="text-sm text-gray-300">Ground Station Aktif</span>
                    </label>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-400 mb-2">Preview Lokasi di Peta</label>
                    <div id="preview-map" style="height: 250px;" class="rounded-xl overflow-hidden border border-white/10"></div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-400 mb-2">Deskripsi</label>
                    <textarea name="description" rows="3"
                              class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500"
                              placeholder="Informasi tambahan tentang stasiun bumi ini...">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-white/10">
                <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>Simpan Stasiun
                </button>
                <a href="{{ route('ground-stations.index') }}"
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
// Preview map
const previewMap = L.map('preview-map').setView([-6.2, 106.8], 5);
L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    attribution: '©CartoDB', maxZoom: 19
}).addTo(previewMap);

let previewMarker = null;

function updateMap() {
    const lat = parseFloat(document.querySelector('[name="latitude"]').value);
    const lng = parseFloat(document.querySelector('[name="longitude"]').value);

    if (!isNaN(lat) && !isNaN(lng)) {
        if (previewMarker) previewMap.removeLayer(previewMarker);
        previewMarker = L.marker([lat, lng]).addTo(previewMap);
        previewMap.setView([lat, lng], 10);
    }
}

document.querySelector('[name="latitude"]').addEventListener('input', updateMap);
document.querySelector('[name="longitude"]').addEventListener('input', updateMap);
</script>
@endpush