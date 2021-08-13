<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'username', 
        'password', 
        'admin_name', 
        'admin_contactno', 
        'admin_location', 
        'location_lat', 
        'location_lng', 
        'image',
        'is_active'
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
