<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('causer');

        if ($request->search) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }
        if ($request->log_name) {
            $query->where('log_name', $request->log_name);
        }

        $activities = $query->latest()->paginate(20)->withQueryString();
        return view('activity-logs.index', compact('activities'));
    }
}