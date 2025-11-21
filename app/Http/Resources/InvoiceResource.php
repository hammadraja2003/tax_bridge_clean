<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class InvoiceResource extends JsonResource
{
    public function toArray($request)
    {
        $disk = env('FILESYSTEM_DISK', config('filesystems.default', 'uploads'));

        // QR code URL
        try {
            $qrCodeUrl = null;
            if (!empty($this->qr_code)) {
                $qrCodeUrl = $disk === 's3'
                    ? Storage::disk($disk)->temporaryUrl($this->qr_code, now()->addMinutes(5))
                    : Storage::disk($disk)->url($this->qr_code);
            }
        } catch (\Throwable $e) {
            $qrCodeUrl = null;
        }

        // Static FBR logo
        $logoUrl = asset('assets/fbr-digital-invoicing-logo.png');

        return [
            'invoice_id' => $this->invoice_id,
            'invoice_no' => $this->invoice_no,
            'invoice_type' => $this->invoice_type,
            'invoice_date' => $this->invoice_date,
            'due_date' => $this->due_date,
            'scenario_id' => $this->scenario_id,
            'invoice_ref_no' => $this->invoice_ref_no,
            'seller_id' => $this->seller_id,
            'buyer_id' => $this->buyer_id,
            'fbr_invoice_number' => $this->fbr_invoice_number,
            'qr_code_url' => $qrCodeUrl,
            'fbr_logo_url' => $logoUrl,
            'is_posted_to_fbr' => $this->is_posted_to_fbr,
            'invoice_status' => $this->invoice_status,
            'response_status' => $this->response_status,
            'response_message' => $this->response_message,
            'totalAmountExcludingTax' => $this->totalAmountExcludingTax,
            'totalAmountIncludingTax' => $this->totalAmountIncludingTax,
            'totalSalesTax' => $this->totalSalesTax,
            'totalfurtherTax' => $this->totalfurtherTax,
            'totalextraTax' => $this->totalextraTax,
            'shipping_charges' => $this->shipping_charges,
            'other_charges' => $this->other_charges,
            'discount_amount' => $this->discount_amount,
            'payment_status' => $this->payment_status,
            'notes' => $this->notes,
            'hash' => $this->hash,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'buyer' => $this->buyer,
            'seller' => $this->seller,

            'details' => $this->details->map(function ($detail) use ($disk) {
                $item = $detail->item;
                return [
                    'invoice_detail_id' => $detail->invoice_detail_id,
                    'invoice_id' => $detail->invoice_id,
                    'item_id' => $detail->item_id,
                    'quantity' => $detail->quantity,
                    'total_value' => $detail->total_value,
                    'value_excl_tax' => $detail->value_excl_tax,
                    'retail_price' => $detail->retail_price,
                    'sales_tax_applicable' => $detail->sales_tax_applicable,
                    'sales_tax_withheld' => $detail->sales_tax_withheld,
                    'extra_tax' => $detail->extra_tax,
                    'further_tax' => $detail->further_tax,
                    'fed_payable' => $detail->fed_payable,
                    'discount' => $detail->discount,
                    'sale_type' => $detail->sale_type,
                    'sro_schedule_no' => $detail->sro_schedule_no,
                    'sro_item_serial_no' => $detail->sro_item_serial_no,
                    'hash' => $detail->hash,
                    'created_at' => $detail->created_at,
                    'updated_at' => $detail->updated_at,
                    'item' => $item,
                ];
            }),
        ];
    }
}
