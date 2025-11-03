<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
class LogController extends Controller
{
    public function show(Request $request)
    {
        $logPath = storage_path('logs/laravel.log');
        if (!File::exists($logPath)) {
            return view('admin.logs.show', ['log' => '', 'message' => 'No log file found.']);
        }
        $lines = (int) $request->get('lines', 200);
        $logs = $this->tailLog($logPath, $lines);
        if ($request->expectsJson()) {
            return response()->json(['message' => 'ok', 'log' => $logs]);
        } else {
            return view('admin.logs.show', ['log' => $logs]);
        }
    }
    public function clear()
    {
        $logPath = storage_path('logs/laravel.log');
        if (!File::exists($logPath)) {
            return response()->json(['message' => 'No log file found.'], 404);
        }
        try {
            $lines = (int)200;
            File::put($logPath, '');
            $logs = $this->tailLog($logPath, $lines);
            return view('admin.logs.show', ['log' => $logs]);
       } catch (\Exception $e) {
            return response()->json(['message' => '❌ Failed to clear log file: ' . $e->getMessage()]);
        }
    }
    private function tailLog($filePath, $lines = 100)
    {
        $handle = fopen($filePath, "r");
        $buffer = '';
        $chunkSize = 4096;
        fseek($handle, 0, SEEK_END);
        $pos = ftell($handle);
        $lineCount = 0;
        while ($pos > 0 && $lineCount <= $lines) {
            $seek = max($pos - $chunkSize, 0);
            $bytesToRead = $pos - $seek;
            fseek($handle, $seek);
            $chunk = fread($handle, $bytesToRead);
            $buffer = $chunk . $buffer;
            $pos = $seek;
            $lineCount = substr_count($buffer, "\n");
        }
        fclose($handle);
        $bufferLines = explode("\n", trim($buffer));
        $lastLines = array_slice($bufferLines, -$lines);
        return implode("\n", $lastLines);
    }
}
