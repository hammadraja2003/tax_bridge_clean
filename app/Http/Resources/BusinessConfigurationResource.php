<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BusinessConfigurationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'bus_config_id'        => $this->bus_config_id,
            'bus_name'             => $this->bus_name,

            // FULL ORIGINAL FIELDS RESTORED ↓
            'db_host'              => $this->db_host,
            'db_name'              => $this->db_name,
            'db_username'          => $this->db_username,
            'db_password'          => $this->db_password,

            'bus_ntn_cnic'         => $this->bus_ntn_cnic,
            'bus_address'          => $this->bus_address,
            'bus_province'         => $this->bus_province,

            'bus_logo'             => $this->bus_logo,
            'bus_logo_url'         => $this->getLogoUrl(),

            'bus_account_title'    => $this->bus_account_title,
            'bus_account_number'   => $this->bus_account_number,
            'bus_reg_num'          => $this->bus_reg_num,
            'bus_contact_num'      => $this->bus_contact_num,
            'bus_contact_person'   => $this->bus_contact_person,
            'bus_IBAN'             => $this->bus_IBAN,
            'bus_swift_code'       => $this->bus_swift_code,
            'bus_acc_branch_name'  => $this->bus_acc_branch_name,
            'bus_acc_branch_code'  => $this->bus_acc_branch_code,

            // SECURITY + ENV FIELDS
            'hash'                 => $this->hash,
            'tampered'             => $this->tampered,
            'fbr_env'              => $this->fbr_env,
            'fbr_api_token_sandbox'=> $this->fbr_api_token_sandbox,
            'fbr_api_token_prod'   => $this->fbr_api_token_prod,

            // TIMESTAMP FIELDS
            'created_at'           => $this->created_at?->toISOString(),
            'updated_at'           => $this->updated_at?->toISOString(),
        ];
    }

    private function getLogoUrl()
    {
        if (!$this->bus_logo) {
            return null;
        }

        try {
            $disk = env('FILESYSTEM_DISK', config('filesystems.default', 'uploads'));

            if ($disk === 's3') {
                return Storage::disk($disk)->temporaryUrl(
                    $this->bus_logo,
                    now()->addHour()
                );
            }

            return Storage::disk($disk)->url($this->bus_logo);

        } catch (\Throwable $e) {
            return null;
        }
    }
}
