<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 
        'middle_name', 
        'last_name', 
        'contact_no', 
        'birthday', 
        'gender', 
        'address', 
        'image', 
        'valid_id_image', 
        'email', 
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function announcementFrom() {
        return $this->morphMany('App\Announcement', 'from');
    }

    public function report() {
        return $this->belongsTo('App\Report', 'id', 'reporter_id');
    }
}
