<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPackageFeature extends Model
{
    use HasFactory;

    protected $connection = 'master';
    protected $table = 'business_package_features';
    protected $primaryKey = 'business_package_features_id';
    public $timestamps = false;
    protected $fillable = [
        'business_package_id',
        'feature_key',
        'limit_type',
        'limit_value',
    ];
}
