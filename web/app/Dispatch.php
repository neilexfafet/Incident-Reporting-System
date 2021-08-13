<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    protected $fillable = [
        'dispatch_id',
        'report_id',
        'officer_id',
        'station_id',
        'status',
    ];

    public function getReport() {
        return $this->hasOne('App\Report', 'id', 'report_id');
    }

    public function getOfficer() {
        return $this->hasOne('App\Officer', 'id', 'officer_id');
    }

    public function getStation() {
        return $this->hasOne('App\Station', 'id', 'station_id');
    }
}
