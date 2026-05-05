<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GroundStation;
use Illuminate\Http\Request;

class GroundStationApiController extends Controller
{
    public function index(Request $request)
    {
        $query = GroundStation::withCount('satellites');

        if ($request->search) $query->where('name', 'like', '%' . $request->search . '%');
        if ($request->country) $query->where('country', $request->country);

        return response()->json([
            'status' => 'success',
            'data' => $query->paginate(15),
        ]);
    }

    public function show($id)
    {
        $station = GroundStation::with('satellites')->withCount('satellites')->find($id);
        if (!$station) {
            return response()->json(['status' => 'error', 'message' => 'Ground Station not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $station]);
    }
}