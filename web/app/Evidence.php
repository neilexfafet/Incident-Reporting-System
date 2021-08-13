<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evidence extends Model
{
    protected $fillable = [
        'report_id',
        'filename',
        'filetype',
    ];

    public function getReport() {
        return $this->hasOne('App\Report', 'id', 'report_id');
    }
}
