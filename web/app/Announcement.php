<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Announcement extends Model
{
    use Notifiable;

    protected $fillable = [
        'subject',
        'message',
        'image',
        'is_active',
    ];

    public function from() {
        return $this->morphTo();
    }

    public function notify() {
        return $this->morphMany('App\Notification', 'notif');
    }

}
