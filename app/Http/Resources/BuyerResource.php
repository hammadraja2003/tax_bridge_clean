<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BuyerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'byr_id'               => $this->byr_id,
            'byr_name'             => $this->byr_name,
            'byr_type'             => $this->byr_type,
            'byr_ntn_cnic'         => $this->byr_ntn_cnic,
            'byr_address'          => $this->byr_address,
            'byr_province'         => $this->byr_province,
            'byr_logo'             => $this->byr_logo,
            'byr_logo_url'         => $this->getLogoUrl(),
            'byr_account_title'    => $this->byr_account_title,
            'byr_account_number'   => $this->byr_account_number,
            'byr_reg_num'          => $this->byr_reg_num,
            'byr_contact_num'      => $this->byr_contact_num,
            'byr_contact_person'   => $this->byr_contact_person,
            'byr_IBAN'             => $this->byr_IBAN,
            'byr_swift_code'       => $this->byr_swift_code,
            'byr_acc_branch_name'  => $this->byr_acc_branch_name,
            'byr_acc_branch_code'  => $this->byr_acc_branch_code,
            'hash'                 => $this->hash,
            'tampered'             => $this->tampered,
            'created_at'           => $this->created_at?->toISOString(),
            'updated_at'           => $this->updated_at?->toISOString(),
        ];
    }

    private function getLogoUrl()
    {
        if (!$this->byr_logo) {
            return null;
        }

        try {
            $disk = env('FILESYSTEM_DISK', config('filesystems.default', 'uploads'));

            if ($disk === 's3') {
                return Storage::disk($disk)->temporaryUrl(
                    $this->byr_logo,
                    now()->addHour()
                );
            }

            return Storage::disk($disk)->url($this->byr_logo);

        } catch (\Throwable $e) {
            return null;
        }
    }
}
