<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessFeatureUsage extends Model
{
    use HasFactory;

    protected $connection = 'master';
    protected $table = 'business_feature_usage';
    protected $primaryKey = 'business_feature_usage_id';
    public $timestamps = false;

    protected $fillable = [
        'business_id',
        'business_package_id',
        'feature_key',
        'period_start_date',
        'period_end_date',
        'used_count',
    ];
}
