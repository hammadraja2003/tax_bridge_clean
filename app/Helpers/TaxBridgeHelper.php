<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;
use App\Models\BusinessConfiguration;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\BusinessFeatureUsage;
use App\Models\BusinessPackage;

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
        $connection = (new ActivityLog())->getConnectionName();
        if ($connection === 'master') {
            throw new \RuntimeException("Tenant connection not set. Refusing to log activity into master DB.");
        }
        $user = Auth::user();
        $userId = $user ? $user->id : null;
        $userName = $user ? $user->name : 'guest';
        $ip = Request::ip();
        $deviceId = Request::header('device-id') ?? 'unknown';
        $dataJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
        $newHash = hash('sha256', $dataJson);
        $hashChanged = ($action !== 'update');
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
                    return Storage::disk($disk)->temporaryUrl($config->bus_logo, now()->addMinutes(5));
                } else {
                    return Storage::disk($disk)->url($config->bus_logo);
                }
            } catch (\Throwable $e) {
                \Log::error('Error fetching business logo', [
                    'error' => $e->getMessage(),
                    'path'  => $config->bus_logo,
                    'disk'  => $disk,
                ]);
            }
        }
        return asset('assets/images/logo/tax-bridge.svg');
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
if (!function_exists('dynamicTemporaryUrl')) {
    function dynamicTemporaryUrl($disk, $path, $expiration = '+5 minutes')
    {
        $driver = config("filesystems.disks.$disk.driver");
        if (in_array($driver, ['s3', 'gcs', 'azure'])) {
            return Storage::disk($disk)->temporaryUrl(
                $path,
                now()->addMinutes(5)
            );
        }
        return Storage::disk($disk)->url($path);
    }
}
if (!function_exists('getScenarioOptions')) {
    /**
     * Get business scenarios for API as JSON-friendly array
     *
     * @param string|null $selectedCode
     * @return array
     */
    function getScenarioOptions($businessId , $selectedCode = null)
    {
        // $businessId = session('bus_config_id');

        if (!$businessId) {
            return [];
        }

        $scenarios = DB::connection('master')
            ->table('business_scenarios as bs')
            ->join('sandbox_scenarios as ss', 'bs.scenario_id', '=', 'ss.scenario_id')
            ->where('bs.bus_config_id', $businessId)
            ->orderBy('ss.scenario_code', 'asc')
            ->get();

        $result = [];

        foreach ($scenarios as $scenario) {
            $result[] = [
                'scenario_code' => $scenario->scenario_code,
                'scenario_description' => $scenario->scenario_description,
                'sale_type' => $scenario->sale_type ?? null,
                'selected' => ($selectedCode === $scenario->scenario_code),
            ];
        }

        return $result;
    }
}
if (!function_exists('successResponse')) {
    function successResponse($data = [], $status = 200, $message = '', $isDecoded = 0, $isPaginated = false)
    {
        $isDecoded = env('API_RESPONSE_ENC', $isDecoded);
        // Return plain JSON if encoding disabled
        if ($isDecoded == 0) {
            return response()->json([
                'success' => true,
                'code'    => $status,
                'message' => $message,
                'data'    => $data,
                'enc'     => 0,
                'isPaginated' => $isPaginated,
            ], $status);
        }
        // Convert array/object to JSON string
        $jsonData = is_array($data) || is_object($data)
            ? json_encode($data)
            : $data;
        // Base64 encode
        $encodedData = base64_encode($jsonData);
        return response()->json([
            'success' => true,
            'code'    => $status,
            'message' => $message,
            'data'    => $encodedData,
            'enc'     => 1,
            'isPaginated' => $isPaginated,
        ], $status);
    }
}
if (!function_exists('errorResponse')) {
    function errorResponse($message = 'An error occurred', $status = 400)
    {
        return response()->json([
            'success' => false,
            'code'    => $status,
            'message' => $message,
        ], $status);
    }
}
if (!function_exists('isApiRequest')) {
    function isApiRequest(): bool
    {
        return request()->is('api/*');
    }
}
if (!function_exists('paginatedResponse')) {
    function paginatedResponse(LengthAwarePaginator $paginator, string $message = '', int $status = 200)
    {
        $data = [
            'data' => $paginator->items(),               // Actual paginated items
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total()
        ];
        return successResponse($data, $status, $message, env('API_RESPONSE_ENC'), true);
    }
}

if (!function_exists('checkFeatureLimit')) {
    /**
     * Check feature availability for a tenant.
     *
     * @param int $tenantId
     * @param string $featureKey
     * @param bool $increment Whether to increment usage (default false)
     * @param int $incrementCount Number to increment usage by (default 1)
     * @return array
     */
    function checkFeatureLimit(int $tenantId, string $featureKey, bool $increment = false, int $incrementCount = 1): array
    {
        $today = Carbon::now();

        // Get active package (trial or normal)
        $package = BusinessPackage::where('business_id', $tenantId)
            ->where('is_active', true)
            ->where(function ($query) use ($today) {
                $query->where(function ($q) use ($today) {
                    $q->where('is_trial', false)
                        ->where('end_date', '>=', $today);
                })
                    ->orWhere(function ($q) use ($today) {
                        $q->where('is_trial', true)
                            ->where('trial_end_date', '>=', $today);
                    });
            })
            ->first();

        if (!$package) {
            return [
                'ok' => false,
                'message' => 'No active package for this business.',
                'usage' => null,
                'package' => null,
            ];
        }

        // Fetch or create usage row for the feature
        // $usage = BusinessFeatureUsage::firstOrCreate(
        //     [
        //         'business_package_id' => $package->business_packages_id,
        //         'feature_key' => $featureKey,
        //     ],
        //     [
        //         'business_id' => $tenantId,
        //         'period_start_date' => $package->start_date,
        //         'period_end_date' => $package->end_date,
        //         'used_count' => 0
        //     ]
        // );
        $usage = BusinessFeatureUsage::firstOrCreate(
            [
                'business_package_id' => $package->business_packages_id,
                'feature_key' => $featureKey,
                'period_start_date' => $package->start_date,
                'period_end_date' => $package->end_date,
            ],
            [
                'business_id' => $tenantId,
                'used_count' => 0
            ]
        );


        // Check limit
        if ($usage->limit_value !== null && $usage->used_count + ($increment ? $incrementCount : 0) > $usage->limit_value) {
            return [
                'ok' => false,
                'message' => ucfirst($featureKey) . ' limit exceeded for your package.',
                'usage' => $usage,
                'package' => $package,
            ];
        }

        // Increment usage only if requested
        if ($increment) {
            $usage->increment('used_count', $incrementCount);
        }

        return [
            'ok' => true,
            'message' => ucfirst($featureKey) . ' is available.',
            'usage' => $usage,
            'package' => $package,
        ];
    }
}
