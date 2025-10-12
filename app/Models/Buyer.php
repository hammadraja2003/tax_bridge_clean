<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    protected $connection = 'tenant';
    protected $table = 'buyers';
    protected $primaryKey = 'byr_id';
    public $timestamps = true;
    protected $fillable = [
        'byr_name',
        'byr_type',
        'byr_ntn_cnic',
        'byr_address',
        'byr_province',
        'byr_logo',
        'byr_account_title',
        'byr_account_number',
        'byr_reg_num',
        'byr_contact_num',
        'byr_contact_person',
        'byr_IBAN',
        'byr_swift_code',
        'byr_acc_branch_name',
        'byr_acc_branch_code',
        'hash',
    ];
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'buyer_id', 'byr_id'); // ✅
    }
    // 🔑 Auto-generate hash
    protected static function booted()
    {
        static::creating(function ($buyer) {
            $buyer->hash = $buyer->generateHash();
        });
        static::updating(function ($buyer) {
            $buyer->hash = $buyer->generateHash();
        });
    }
    // ✅ Generate hash from critical fields
    public function generateHash()
    {
        return md5(
            $this->byr_name .
                $this->byr_type .
                $this->byr_ntn_cnic .
                $this->byr_address .
                $this->byr_province .
                $this->byr_account_title .
                $this->byr_account_number .
                $this->byr_reg_num .
                $this->byr_contact_num .
                $this->byr_contact_person .
                $this->byr_IBAN .
                $this->byr_swift_code .
                $this->byr_acc_branch_name .
                $this->byr_acc_branch_code
        );
    }
}