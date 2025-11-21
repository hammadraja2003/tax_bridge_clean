<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;
    protected $connection = 'tenant';
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
            $item->hash = self::generateHash($item);
        });

        static::updating(function ($item) {
            $item->hash = self::generateHash($item);
        });
    }

    protected static function generateHash($item)
    {
        return md5(
            $item->item_hs_code .
                $item->item_description .
                number_format($item->item_price, 2, '.', '') .
                $item->item_tax_rate.
                $item->item_uom
        );
    }
}
