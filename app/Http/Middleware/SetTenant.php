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
        $tenantManager = app(\App\Services\TenantManager::class);
        $tenantId = Auth::user()->tenant_id ?? session('tenant_id');
        if ($tenantId) {
            $business = DB::connection('master')
                ->table('business_configurations')
                ->where('bus_config_id', $tenantId)
                ->first();
            if ($business) {
                $dbName = $business->db_name;
                // ✅ Check if database actually exists
                $dbExists = DB::select("SHOW DATABASES LIKE '{$dbName}'");
                if (empty($dbExists)) {
                    Auth::logout();
                    return redirect()->route('login')
                        ->withErrors([
                            'db' => "Your tenant database has not been created yet. Please contact admin."
                        ]);
                }
                // If DB exists → proceed normally
                $tenantManager->setTenant($business->bus_config_id);
                session([
                    'tenant_id'     => $tenantId,
                    'bus_config_id' => $business->bus_config_id,
                    'tenant_db'     => $business->db_name,
                ]);
            }
        }
        return $next($request);
    }
}