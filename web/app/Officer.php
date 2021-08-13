<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'rank_id',
        'id_no',
        'badge_no',
        'email',
        'birthday',
        'gender',
        'address',
        'contact_no',
        'img',
        'is_active',
        'status',
        'station_id',
    ];

    public function getRank() {
        return $this->hasOne('App\Rank', 'id', 'rank_id');
    }

    public function getStation() {
        return $this->hasOne('App\Station', 'id', 'station_id');
    }
}
