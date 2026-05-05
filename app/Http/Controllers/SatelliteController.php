<?php

namespace App\Http\Controllers;

use App\Models\Satellite;
use App\Models\GroundStation;
use App\Exports\SatelliteExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SatelliteController extends Controller
{
    public function index(Request $request)
    {
        $query = Satellite::with('groundStation');

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->country) {
            $query->where('country', $request->country);
        }
        if ($request->orbit_type) {
            $query->where('orbit_type', $request->orbit_type);
        }
        if ($request->status !== null && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $satellites = $query->latest()->paginate(10)->withQueryString();
        $countries = Satellite::distinct()->pluck('country')->sort();
        $orbitTypes = ['LEO', 'MEO', 'GEO', 'HEO'];

        return view('satellites.index', compact('satellites', 'countries', 'orbitTypes'));
    }

    public function create()
    {
        $groundStations = GroundStation::where('is_active', true)->get();
        return view('satellites.create', compact('groundStations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'launch_date' => 'nullable|date',
            'orbit_type' => 'required|in:LEO,MEO,GEO,HEO',
            'tle_line1' => 'nullable|string',
            'tle_line2' => 'nullable|string',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'ground_station_id' => 'nullable|exists:ground_stations,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('satellites', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        Satellite::create($validated);
        return redirect()->route('satellites.index')->with('success', 'Satelit berhasil ditambahkan!');
    }

    public function show(Satellite $satellite)
    {
        $satellite->load('groundStation');
        return view('satellites.show', compact('satellite'));
    }

    public function edit(Satellite $satellite)
    {
        $groundStations = GroundStation::where('is_active', true)->get();
        return view('satellites.edit', compact('satellite', 'groundStations'));
    }

    public function update(Request $request, Satellite $satellite)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'launch_date' => 'nullable|date',
            'orbit_type' => 'required|in:LEO,MEO,GEO,HEO',
            'tle_line1' => 'nullable|string',
            'tle_line2' => 'nullable|string',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'ground_station_id' => 'nullable|exists:ground_stations,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($satellite->image) {
                \Storage::disk('public')->delete($satellite->image);
            }
            $validated['image'] = $request->file('image')->store('satellites', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $satellite->update($validated);
        return redirect()->route('satellites.index')->with('success', 'Satelit berhasil diperbarui!');
    }

    public function destroy(Satellite $satellite)
    {
        if ($satellite->image) {
            \Storage::disk('public')->delete($satellite->image);
        }
        $satellite->delete();
        return redirect()->route('satellites.index')->with('success', 'Satelit berhasil dihapus!');
    }

    public function exportExcel()
    {
        return Excel::download(new SatelliteExport, 'satellites-' . date('Ymd') . '.xlsx');
    }

    public function exportPdf()
    {
        $satellites = Satellite::with('groundStation')->get();
        $pdf = Pdf::loadView('satellites.pdf', compact('satellites'))->setPaper('a4', 'landscape');
        return $pdf->download('satellites-' . date('Ymd') . '.pdf');
    }
}