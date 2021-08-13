<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Station extends Authenticatable
{
    use Notifiable;

    protected $guard = 'station';

    protected $fillable = [
        'username', 
        'password', 
        'station_name', 
        'station_contactno', 
        'location_name', 
        'location_lat', 
        'location_lng', 
        'image',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    public function announcementFrom() {
        return $this->morphMany('App\Announcement', 'from');
    }

    public function accountLog() {
        return $this->morphMany('App\Account_log', 'account');
    }
}
