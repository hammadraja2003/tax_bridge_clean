<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SandboxScenario extends Model
{
    use HasFactory;
    protected $connection = 'master';
    protected $table = 'sandbox_scenarios';
    protected $primaryKey = 'scenario_id'; 
    public $timestamps = false; 
    protected $fillable = [
        'scenario_code',  
        'scenario_name',
        'description',
    ];
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