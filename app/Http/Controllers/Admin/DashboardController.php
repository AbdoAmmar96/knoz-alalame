<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Resource;
use App\Http\Controllers\Controller;
use App\Models\Lead;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = [];
        foreach (Resource::all() as $key => $def) {
            $counts[$key] = [
                'label' => $def['label'],
                'icon' => $def['icon'],
                'total' => $def['model']::count(),
                'hidden' => $def['model']::where('is_active', false)->count(),
            ];
        }

        return view('admin.dashboard', [
            'counts' => $counts,
            'newLeads' => Lead::where('status', 'new')->count(),
            'totalLeads' => Lead::count(),
            'latestLeads' => Lead::latest()->take(6)->get(),
        ]);
    }
}
