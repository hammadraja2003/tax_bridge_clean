<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::with('user')
            ->latestFirst()
            ->paginate(10);
        foreach ($logs as $log) {
            $calculatedOldHash = $log->old_data ? hash('sha256', json_encode($log->old_data)) : null;
            $calculatedNewHash = $log->new_data ? hash('sha256', json_encode($log->new_data)) : null;
            $log->tampered = (
                ($log->row_hash_old && $log->row_hash_old !== $calculatedOldHash) ||
                ($log->row_hash_new && $log->row_hash_new !== $calculatedNewHash)
            );
        }
        if (isApiRequest()) {
            return paginatedResponse($logs, 'Audit Logs Data Fetched');
        }
        return view('audit_logs.index', compact('logs'));
    }
    public function show($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(404);
        }
        $log = AuditLog::findOrFail($id);
        $calculatedOldHash = $log->old_data ? hash('sha256', json_encode($log->old_data)) : null;
        $calculatedNewHash = $log->new_data ? hash('sha256', json_encode($log->new_data)) : null;
        $log->tampered = (
            ($log->row_hash_old && $log->row_hash_old !== $calculatedOldHash) ||
            ($log->row_hash_new && $log->row_hash_new !== $calculatedNewHash)
        );
        return view('audit_logs.show', compact('log'));
    }
}
