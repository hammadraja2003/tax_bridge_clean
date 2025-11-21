<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Services\TenantManager;
use Illuminate\Support\Facades\DB;

class SetTenant
{
    protected $tenantManager;
    public function __construct(TenantManager $tenantManager)
    {
        $this->tenantManager = $tenantManager;
    }
    public function handle($request, Closure $next)
    {
        if ($request->is('login') || $request->is('password/*') || $request->is('company/configuration*')) {
            return $next($request);
        }
        $tenantManager = app(\App\Services\TenantManager::class);
        $tenantId = Auth::user()->tenant_id ?? session('tenant_id');
        if ($tenantId) {
            $business = DB::connection('master')
                ->table('business_configurations')
                ->where('bus_config_id', $tenantId)
                ->first();
            if ($business) {
                $dbName = $business->db_name;
                $dbExists = DB::select("SHOW DATABASES LIKE '{$dbName}'");
                if (empty($dbExists)) {
                    Auth::logout();
                    return redirect()->route('login')
                        ->withErrors([
                            'db' => "Your tenant database has not been created yet. Please contact admin."
                        ]);
                }
                $tenantManager->setTenant($business->bus_config_id);
                session([
                    'tenant_id'     => $tenantId,
                    'bus_config_id' => $business->bus_config_id,
                    'tenant_db'     => $business->db_name,
                    'bus_name'     => $business->bus_name,
                ]);

                // ===========================
                // Active package check starts
                // ===========================
                $today = \Carbon\Carbon::now();

                $activePackage = DB::connection('master')
                    ->table('business_packages')
                    ->where('business_id', $business->bus_config_id)
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

                if (!$activePackage) {
                    Auth::logout();
                    return redirect()->route('login')->withErrors([
                        'db' => 'Your business does not have an active package. Please contact admin.'
                    ]);
                } else {
                    session([
                        'is_trial'     => $activePackage->is_trial,
                        'trial_end_date'     => $activePackage->trial_end_date,
                        'start_date'     => $activePackage->start_date,
                        'end_date'     => $activePackage->end_date,
                    ]);
                }
                // ===========================
                // Active package check ends
                // ===========================

            }
        }
        return $next($request);
    }
}
