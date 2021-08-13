<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $fillable = [
        'type', 'description', 'is_active',
    ];

    public function reports() {
        return $this->belongsTo('App\Report', 'id', 'incident_id');
    }
}
