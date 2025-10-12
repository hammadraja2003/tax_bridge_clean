<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessConfiguration extends Model
{
    use HasFactory;
    // 🔹 This model belongs to master DB
    protected $connection = 'master';
    protected $primaryKey = 'bus_config_id';
    protected $fillable = [
        'bus_name',
        'bus_ntn_cnic',
        'bus_address',
        'bus_province',
        'bus_logo',
        'bus_account_title',
        'bus_account_number',
        'bus_reg_num',
        'bus_contact_num',
        'bus_contact_person',
        'bus_IBAN',
        'bus_swift_code',
        'bus_acc_branch_name',
        'bus_acc_branch_code',
        'hash',
        'db_host',
        'db_name',
        'db_username',
        'db_password',
        'fbr_env',
        'fbr_api_token_sandbox',
        'fbr_api_token_prod',
    ];
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'seller_id', 'bus_config_id'); // ✅
    }
    public function scenarios()
    {
        return $this->belongsToMany(
            SandboxScenario::class,
            'business_scenarios',
            'bus_config_id',
            'scenario_id'
        )->withTimestamps();
    }
    // 🔑 Auto-hash on create/update
    protected static function booted()
    {
        static::creating(function ($config) {
            $config->hash = $config->generateHash();
        });
        static::updating(function ($config) {
            $config->hash = $config->generateHash();
        });
    }
    // ✅ Generate hash from critical fields
    public function generateHash()
    {
        return md5(
            $this->bus_name .
                $this->bus_ntn_cnic .
                $this->bus_address .
                $this->bus_province .
                $this->bus_account_title .
                $this->bus_account_number .
                $this->bus_reg_num .
                $this->bus_contact_num .
                $this->bus_contact_person .
                $this->bus_IBAN .
                $this->bus_swift_code .
                $this->bus_acc_branch_name .
                $this->bus_acc_branch_code
        );
    }
}