<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'status',
    ];

    public function notif() {
        return $this->morphTo();
    }

    public function sendto() {
        return $this->morphTo();
    }
}
