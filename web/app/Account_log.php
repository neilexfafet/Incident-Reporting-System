<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account_log extends Model
{
    protected $fillable = [
        'activity',
        'officer_id',
    ];

    public function getOfficers() {
        return $this->hasOne('App\Officer', 'id', 'officer_id');
    }

    public function account() {
        return $this->morphTo();
    }
}
