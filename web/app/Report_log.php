<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report_log extends Model
{
    protected $fillable = [
        'activity',
        'report_id',
        'dispatch_id',
    ];

    public function getReport() {
        return $this->hasOne('App\Report', 'id', 'report_id');
    }

    public function getDispatch() {
        return $this->hasOne('App\Dispatch', 'id', 'dispatch_id');
    }
}
