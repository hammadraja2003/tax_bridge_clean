<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceDetail extends Model
{
    use HasFactory;
    protected $connection = 'tenant'; 
    protected $primaryKey = 'invoice_detail_id';
    protected $fillable = [
        'invoice_id',
        'item_id',
        'quantity',
        'total_value',
        'value_excl_tax',
        'retail_price',
        'sales_tax_applicable',
        'sales_tax_withheld',
        'extra_tax',
        'further_tax',
        'fed_payable',
        'discount',
        'sale_type',
        'sro_schedule_no',
        'sro_item_serial_no',
        'hash',
    ];
    // 🔗 Relationships
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }
    public function generateHash(): string
    {
        $fields = [
            'invoice_id',
            'item_id',
            'quantity',
            'total_value',
            'value_excl_tax',
            'retail_price',
            'sales_tax_applicable',
            'sales_tax_withheld',
            'extra_tax',
            'further_tax',
            'fed_payable',
            'discount',
            'sale_type',
            'sro_schedule_no',
            'sro_item_serial_no',
        ];
        $data = [];
        foreach ($fields as $field) {
            $val = $this->$field ?? '';
            // Normalize dates
            if ($val instanceof \Carbon\Carbon) {
                $val = $val->format('Y-m-d');
            }
            // Normalize numbers
            if (is_numeric($val)) {
                $val = number_format((float)$val, 2, '.', '');
            }
            // Normalize booleans
            if (is_bool($val)) {
                $val = $val ? '1' : '0';
            }
            $data[$field] = (string) $val;
        }
        return hash(
            'sha256',
            json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }
    // 🚨 Check if tampered
    public function isTampered(): bool
    {
        $current = $this->generateHash();
        return $current !== $this->hash;
    }
    protected static function booted()
    {
        static::saving(function (self $detail) {
            $newHash = $detail->generateHash();
            if ($detail->hash !== $newHash) {
                $detail->hash = $newHash;
            }
        });
    }
}