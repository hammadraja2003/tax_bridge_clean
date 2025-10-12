<?php
namespace App\Services;
use App\Models\BusinessConfiguration;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
class TenantManager
{
    protected $tenant;
    public function setTenant($tenantId)
    {
        $business = BusinessConfiguration::find($tenantId);
        if ($business) {
            Config::set("database.connections.tenant", [
                'driver'   => 'mysql',
                'host'     => $business->db_host ?: '127.0.0.1',
                'database' => $business->db_name,
                'username' => $business->db_username,
                'password' => $business->db_password,
                'charset'  => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix'   => '',
                'strict'   => true,
                'engine'   => null,
            ]);
            DB::purge('tenant');      // clear any previous connection
            DB::reconnect('tenant');  // reconnect with new creds
            $this->tenant = $business;
        }
    }
    public function getTenant()
    {
        return $this->tenant;
    }
}