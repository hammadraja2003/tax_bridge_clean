<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageFeature extends Model
{
    protected $connection = 'master';
    protected $table = 'package_features';
    protected $primaryKey = 'package_features_id';
    protected $fillable = [
        'package_id',
        'feature_key',
        'limit_type',
        'limit_value',
    ];
    public $timestamps = false; 

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'package_id');
    }
}
