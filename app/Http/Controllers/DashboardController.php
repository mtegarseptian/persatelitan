<?php

namespace App\Http\Controllers;

use App\Models\Satellite;
use App\Models\GroundStation;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSatellites = Satellite::count();
        $activeSatellites = Satellite::where('is_active', true)->count();
        $inactiveSatellites = Satellite::where('is_active', false)->count();
        $totalGroundStations = GroundStation::count();

        $orbitStats = Satellite::selectRaw('orbit_type, count(*) as total')
            ->groupBy('orbit_type')
            ->get();

        $countryStats = Satellite::selectRaw('country, count(*) as total')
            ->groupBy('country')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $monthlyLaunches = Satellite::selectRaw('YEAR(launch_date) as year, MONTH(launch_date) as month, count(*) as total')
            ->whereNotNull('launch_date')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $recentActivities = Activity::with('causer')
            ->latest()
            ->limit(10)
            ->get();

        $recentSatellites = Satellite::with('groundStation')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalSatellites',
            'activeSatellites',
            'inactiveSatellites',
            'totalGroundStations',
            'orbitStats',
            'countryStats',
            'monthlyLaunches',
            'recentActivities',
            'recentSatellites'
        ));
    }
}