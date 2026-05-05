<?php

namespace App\Http\Controllers;

use App\Models\GroundStation;
use App\Exports\GroundStationExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class GroundStationController extends Controller
{
    public function index(Request $request)
    {
        $query = GroundStation::withCount('satellites');

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('country', 'like', '%' . $request->search . '%');
        }
        if ($request->country) {
            $query->where('country', $request->country);
        }

        $groundStations = $query->latest()->paginate(10)->withQueryString();
        $countries = GroundStation::distinct()->pluck('country')->sort();

        return view('ground-stations.index', compact('groundStations', 'countries'));
    }

    public function create()
    {
        return view('ground-stations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        GroundStation::create($validated);
        return redirect()->route('ground-stations.index')->with('success', 'Ground Station berhasil ditambahkan!');
    }

    public function show(GroundStation $groundStation)
    {
        $groundStation->load('satellites');
        return view('ground-stations.show', compact('groundStation'));
    }

    public function edit(GroundStation $groundStation)
    {
        return view('ground-stations.edit', compact('groundStation'));
    }

    public function update(Request $request, GroundStation $groundStation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $groundStation->update($validated);
        return redirect()->route('ground-stations.index')->with('success', 'Ground Station berhasil diperbarui!');
    }

    public function destroy(GroundStation $groundStation)
    {
        $groundStation->delete();
        return redirect()->route('ground-stations.index')->with('success', 'Ground Station berhasil dihapus!');
    }

    public function exportExcel()
    {
        return Excel::download(new GroundStationExport, 'ground-stations-' . date('Ymd') . '.xlsx');
    }

    public function exportPdf()
    {
        $groundStations = GroundStation::withCount('satellites')->get();
        $pdf = Pdf::loadView('ground-stations.pdf', compact('groundStations'));
        return $pdf->download('ground-stations-' . date('Ymd') . '.pdf');
    }
}