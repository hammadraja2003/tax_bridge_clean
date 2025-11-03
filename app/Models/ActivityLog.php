<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $connection = 'tenant';  
    protected $table = 'activity_logs';
    protected $fillable = [
        'user_id',
        'user_name',
        'ip_address',
        'device_id',
        'action',
        'description',
        'record_id',
        'table_name',
        'data_hash',
        'hash_changed',
        'data',
        'created_at',
    ];
    protected $casts = [
        'data' => 'array', 
    ];
    protected $appends = ['diff'];
    public function getDiffAttribute()
    {
        if ($this->action === 'update' && isset($this->data['old']) && isset($this->data['new'])) {
            $diff = [];
            foreach ($this->data['new'] as $key => $newValue) {
                $oldValue = $this->data['old'][$key] ?? null;
                if ($oldValue != $newValue) {
                    $diff[$key] = [
                        'old' => $oldValue,
                        'new' => $newValue
                    ];
                }
            }
            return $diff;
        }
        return null;
    }
}