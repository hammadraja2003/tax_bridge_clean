<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SandboxScenario extends Model
{
    use HasFactory;
    protected $connection = 'master'; // since it's in master_db
    protected $table = 'sandbox_scenarios'; // ensure correct table
    protected $primaryKey = 'scenario_id'; // match your table PK
    public $timestamps = false; // if sandbox_scenarios doesn't have created_at/updated_at
    protected $fillable = [
        'scenario_code',   // e.g., SN001
        'scenario_name',   // e.g., "Goods at standard rate..."
        'description',
    ];
    // Businesses selecting this scenario
    public function businesses()
    {
        return $this->belongsToMany(
            BusinessConfiguration::class,
            'business_scenarios',
            'scenario_id',
            'bus_config_id'
        );
    }
}