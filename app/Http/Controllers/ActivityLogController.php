<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Mengambil log terbaru beserta data user yang melakukannya
        $logs = ActivityLog::with('user')->latest()->paginate(15);
        return view('activity-logs.index', compact('logs'));
    }
}