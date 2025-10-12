<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;
use App\Models\BusinessConfiguration;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

if (!function_exists('logError')) {
    function logError(array $context = [])
    {
        $message = "";
        if (isset($context['exception']) && $context['exception'] instanceof \Exception) {
            $exception = $context['exception'];
            $message .= ' | Exception: ' . $exception->getMessage();
            $message .= ' | File: ' . $exception->getFile();
            $message .= ' | Line: ' . $exception->getLine();
            unset($context['exception']);
        }
        Log::channel('grclogs')->error($message, $context);
        Log::channel('slack')->error($message, $context);
    }
}
if (!function_exists('logActivity')) {
    function logActivity($action, $description, array $data, $recordId = null, $tableName = null)
    {
        // 🔹 Ensure we're not logging into master DB
        $connection = (new ActivityLog())->getConnectionName();
        if ($connection === 'master') {
            throw new \RuntimeException("Tenant connection not set. Refusing to log activity into master DB.");
        }
        $user = Auth::user();
        $userId = $user ? $user->id : null;
        $userName = $user ? $user->name : 'guest';
        $ip = Request::ip();
        $deviceId = Request::header('device-id') ?? 'unknown';        // 🔹 Normalize JSON for consistent hashing
        $dataJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
        $newHash = hash('sha256', $dataJson);
        $hashChanged = ($action !== 'update'); // default: add/delete = true
        $oldHash = null;
        if ($action === 'update' && $recordId && $tableName) {
            $oldLog = ActivityLog::where('record_id', $recordId)
                ->where('table_name', $tableName)
                ->orderBy('created_at', 'desc')
                ->first();
            if ($oldLog) {
                $oldHash = $oldLog->data_hash;
                $hashChanged = $oldHash !== $newHash;
            }
        }
        ActivityLog::create([
            'user_id'      => $userId,
            'user_name'    => $userName,
            'ip_address'   => $ip,
            'device_id'    => $deviceId,
            'action'       => $action,
            'description'  => $description,
            'record_id'    => $recordId,
            'table_name'   => $tableName,
            'data_hash'    => $newHash,
            'hash_changed' => $hashChanged,
            'data'         => $data,
            'created_at'   => now(),
        ]);
        return true;
    }
}
if (!function_exists('renderScenarioOptions')) {
    function renderScenarioOptions($selectedCode = null)
    {
        $businessId = session('bus_config_id');
        if (!$businessId) {
            return '<option value="">No Business Selected</option>';
        }
        $scenarios = DB::connection('master')
            ->table('business_scenarios as bs')
            ->join('sandbox_scenarios as ss', 'bs.scenario_id', '=', 'ss.scenario_id')
            ->where('bs.bus_config_id', $businessId)
            ->orderBy('ss.scenario_code', 'asc')
            ->get();
        $options = '<option value="">Select Scenario</option>';
        foreach ($scenarios as $scenario) {
            $isSelected = ($selectedCode === $scenario->scenario_code) ? 'selected' : '';
            $options .= '<option value="' . e($scenario->scenario_code) . '" 
                            data-sale-type="' . e($scenario->sale_type ?? '') . '" ' . $isSelected . '>'
                . e($scenario->scenario_code) . ' - ' . e($scenario->scenario_description)
                . '</option>';
        }
        return $options;
    }
}
if (!function_exists('getFbrEnv')) {
    function getFbrEnv(): string
    {
        $tenantId = Auth::user()->tenant_id ?? session('tenant_id');
        if (!$tenantId) {
            return 'sandbox';
        }
        $config = BusinessConfiguration::where('bus_config_id', $tenantId)->first();
        if (!$config) {
            return 'sandbox';
        }
        return $config->fbr_env ?? 'sandbox';
    }
}
if (!function_exists('getBusinessConfig')) {
    function getBusinessConfig()
    {
        $tenantId = Auth::check() ? Auth::user()->tenant_id : session('tenant_id');
        if (!$tenantId) {
            return null;
        }
        return BusinessConfiguration::where('bus_config_id', $tenantId)->first();
    }
}

if (!function_exists('businessLogo')) {
    function businessLogo()
    {
        $config = getBusinessConfig();
        $disk = env('FILESYSTEM_DISK', config('filesystems.default', 'uploads'));

        if ($config && $config->bus_logo) {
            try {
                if ($disk === 's3') {
                    // Generate a temporary signed URL (valid for 5 minutes)
                    return Storage::disk($disk)->temporaryUrl($config->bus_logo, now()->addMinutes(5));
                } else {
                    // Local/public disks can use normal URL
                    return Storage::disk($disk)->url($config->bus_logo);
                }
            } catch (\Throwable $e) {
                // Log error and continue to fallback
                \Log::error('Error fetching business logo', [
                    'error' => $e->getMessage(),
                    'path'  => $config->bus_logo,
                    'disk'  => $disk,
                ]);
            }
        }

        // fallback logo
        return asset('assets/images/logo/secureism_logo.svg');
    }
}



if (!function_exists('provinceOptions')) {
    function provinceOptions($selected = null)
    {
        $provinces = [
            "BALOCHISTAN",
            "AZAD JAMMU AND KASHMIR",
            "CAPITAL TERRITORY",
            "KHYBER PAKHTUNKHWA",
            "PUNJAB",
            "SINDH",
            "GILGIT BALTISTAN",
        ];

        $html = '<option value="">-- Select Province --</option>';

        foreach ($provinces as $province) {
            $isSelected = (strtoupper($selected) === strtoupper($province)) ? 'selected' : '';
            $html .= "<option value=\"{$province}\" {$isSelected}>{$province}</option>";
        }

        return $html;
    }
}
// if (!function_exists('getUploadPath')) {
//     function getUploadPath($folder = '')
//     {
//         $base = env('UPLOAD_PATH', public_path('uploads'));
//         return rtrim($base, '/') . ($folder ? '/' . trim($folder, '/') : '');
//     }
// }



if (!function_exists('dynamicTemporaryUrl')) {
    function dynamicTemporaryUrl($disk, $path, $expiration = '+5 minutes')
    {
        $driver = config("filesystems.disks.$disk.driver");
        // Log the disk and driver
        //Log::info("dynamicTemporaryUrl: Using disk [$disk] with driver [$driver] for path [$path]");


        if (in_array($driver, ['s3', 'gcs', 'azure'])) {
            // Supported drivers
            return Storage::disk($disk)->temporaryUrl(
                $path,
                now()->addMinutes(5)
            );
        }

        // Fallback for local/public
        return Storage::disk($disk)->url($path);
    }
}
