<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AdminBusiness extends Model
{
    protected $connection = 'master';
    protected $table = 'business_configurations';
    protected $primaryKey = 'bus_config_id';
    protected $fillable = [
        'bus_name',
        'bus_ntn_cnic',
        'bus_address',
        'bus_contact_person',
        'bus_contact_num',
        'bus_account_title',
        'bus_account_number',
        'bus_IBAN',
        'bus_swift_code',
        'bus_acc_branch_name',
        'bus_acc_branch_code',
        'hash',
    ];
    public function users()
    {
        return $this->hasMany(
            User::class,    
            'tenant_id',
            'bus_config_id'
        );
    }
    public function scenarios()
    {
        return $this->belongsToMany(
            SandboxScenario::class,
            'business_scenarios', 
            'bus_config_id',
            'scenario_id'
        );
    }
}
