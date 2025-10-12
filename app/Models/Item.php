<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;
    protected $connection = 'tenant';  // 👈 important
    protected $primaryKey = 'item_id';
    protected $fillable = [
        'item_hs_code',
        'item_description',
        'item_price',
        'item_tax_rate',
        'item_uom',
        'hash',
    ];
    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class, 'item_id', 'item_id');
    }
    protected static function booted()
    {
        static::creating(function ($item) {
            $item->hash = md5(
                $item->item_hs_code .
                    $item->item_description .
                    $item->item_price .
                    $item->item_tax_rate .
                    $item->item_uom
            );
        });
        static::updating(function ($item) {
            $item->hash = md5(
                $item->item_hs_code .
                    $item->item_description .
                    $item->item_price .
                    $item->item_tax_rate .
                    $item->item_uom
            );
        });
    }
}