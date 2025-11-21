<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use App\Services\TenantManager;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class SetTenantForApi
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user(); // Sanctum User
        if ($user && $user->tenant_id) {
            app(TenantManager::class)->setTenant($user->tenant_id);

               // Check active package
            $today = Carbon::now();

            $activePackage = DB::connection('master')
                ->table('business_packages')
                ->where('business_id', $user->tenant_id)
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
                 return errorResponse('Your business does not have an active package.', 403);
            }
        }
        return $next($request);
    }
}
