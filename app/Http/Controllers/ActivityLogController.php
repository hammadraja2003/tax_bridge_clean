<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::latest()->paginate(10);
        foreach ($logs as $log) {
            $currentHash = hash('sha256', json_encode($log->data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
            $log->hash_changed = $currentHash !== $log->data_hash;
        }
        return view('activity_logs.index', compact('logs'));
    }
}