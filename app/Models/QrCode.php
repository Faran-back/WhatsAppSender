<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $fillable = [
        'session_id'
    ];

    public function device(){
        return $this->belongsTo(Device::class);
    }
}
