<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'incident_id',
        'description',
        'location',
        'location_lat',
        'location_lng',
        'reporter_id',
        'station_id',
        'incident_date',
        'status',
    ];

    public function getIncident() {
        return $this->hasOne('App\Incident', 'id', 'incident_id');
    }

    public function getUser() {
        return $this->hasOne('App\User', 'id', 'reporter_id');
    }

    public function getStation() {
        return $this->hasOne('App\Station', 'id', 'station_id');
    }

    public function dispatch() {
        return $this->belongsTo('App\Dispatch', 'id', 'report_id');
    }

    public function evidence() {
        return $this->belongsTo('App\Evidence', 'id', 'report_id');
    }

    public function notify() {
        return $this->morphMany('App\Notification', 'notif');
    }
}
