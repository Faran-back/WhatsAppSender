<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'device_name',
        'phone_number',
        'description',
        'status',
        'token',
        'full',
    ];

    public function qr_code(){
        return $this->hasOne(QrCode::class);
    }
}
