<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
class HistoryExport extends Model
{
    protected $table = 'el_history_export';
    protected $fillable = [
        'report_name',
        'file_name',
        'error',
        'status',
    ];

    public function user() {
        return $this->hasOne('App\Profile', 'user_id', 'user_id');
    }
}
