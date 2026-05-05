<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Satellite;
use Illuminate\Http\Request;

class SatelliteApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Satellite::with('groundStation');

        if ($request->search) $query->where('name', 'like', '%' . $request->search . '%');
        if ($request->orbit_type) $query->where('orbit_type', $request->orbit_type);
        if ($request->country) $query->where('country', $request->country);
        if ($request->status !== null) $query->where('is_active', $request->status);

        return response()->json([
            'status' => 'success',
            'data' => $query->paginate(15),
        ]);
    }

    public function show($id)
    {
        $satellite = Satellite::with('groundStation')->find($id);
        if (!$satellite) {
            return response()->json(['status' => 'error', 'message' => 'Satellite not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $satellite]);
    }

    public function stats()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'total' => Satellite::count(),
                'active' => Satellite::where('is_active', true)->count(),
                'inactive' => Satellite::where('is_active', false)->count(),
                'by_orbit' => Satellite::selectRaw('orbit_type, count(*) as total')->groupBy('orbit_type')->get(),
                'by_country' => Satellite::selectRaw('country, count(*) as total')->groupBy('country')->orderByDesc('total')->limit(10)->get(),
            ]
        ]);
    }
}