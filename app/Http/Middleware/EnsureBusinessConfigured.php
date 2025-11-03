<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class EnsureBusinessConfigured
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $tenantId = session('tenant_id');
        if ($request->is('login') || $request->is('password/*') || $request->is('company/configuration*')) {
            return $next($request);
        }
        $business = \DB::connection('master')
            ->table('business_configurations')
            ->where('bus_config_id', $tenantId)
            ->first();
        if (!$business) {
            return redirect('company/configuration')
                ->with('error', 'Your business configuration is missing.');
        }
        $requiredFields = [
            'bus_name',
            'db_host',
            'db_name',
            'db_username',
            'fbr_env'
        ];
        foreach ($requiredFields as $field) {
            if (empty($business->$field)) {
                return redirect('company/configuration')
                    ->with('error', "Your business configuration is incomplete. Missing: {$field}");
            }
        }
        if ($business->fbr_env === 'sandbox' && empty($business->fbr_api_token_sandbox)) {
            return redirect('company/configuration')
                ->with('error', 'Sandbox API token is missing.');
        }
        if ($business->fbr_env === 'production' && empty($business->fbr_api_token_prod)) {
            return redirect('company/configuration')
                ->with('error', 'Production API token is missing.');
        }
        $scenarioCount = \DB::connection('master')
            ->table('business_scenarios')
            ->where('bus_config_id', $business->bus_config_id)
            ->count();
        if ($scenarioCount === 0) {
            return redirect('company/configuration')
                ->with('error', 'No scenarios found. Please configure your business scenarios.');
        }
        return $next($request);
    }
}
