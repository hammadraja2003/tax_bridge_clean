<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FbrPostError extends Model
{
    protected $connection = 'tenant';  // 👈 important
    protected $table = 'fbr_post_errors';
    protected $primaryKey = 'id'; // 👈 if table uses a custom PK, change it here
    public $timestamps = false;   // 👈 we’re using error_time instead of default timestamps
    // Allow mass assignment
    protected $guarded = [];
    // Cast JSON fields and datetime properly
    protected $casts = [
        'invoice_statuses' => 'array',
        'raw_response'     => 'array',
        'error_time'       => 'datetime',
    ];
    /**
     * Quick helper to log errors into DB
     *
     * @param array $data
     * @return static
     */
    public static function logError(array $data)
    {
        return self::create([
            'type'             => $data['type'] ?? 'validation',
            'status_code'      => $data['status_code'] ?? null,
            'status'           => $data['status'] ?? 'failed',
            'error_code'       => $data['error_code'] ?? null,
            'error'            => $data['error'] ?? null,
            'invoice_statuses' => $data['invoice_statuses'] ?? null,
            'raw_response'     => $data['raw_response'] ?? null,
            'fbr_env'          => $data['fbr_env'] ?? getFbrEnv(),
            'error_time'       => date('Y-m-d H:i:s'),
        ]);
    }
}