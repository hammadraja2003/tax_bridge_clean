<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $connection = 'master';
    protected $table = 'packages';
    protected $primaryKey = 'package_id';
    protected $fillable = [
        'package_name',
        'package_description',
        'package_price',
        'package_billing_cycle',
    ];

    public function features()
    {
        return $this->hasMany(PackageFeature::class, 'package_id', 'package_id');
    }
}
