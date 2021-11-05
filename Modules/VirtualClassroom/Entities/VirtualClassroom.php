<?php

namespace Modules\VirtualClassroom\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class VirtualClassroom extends BaseModel
{
    protected $table = 'el_virtual_classroom';
    protected $fillable = [
        'code',
        'name',
        'start_date',
        'end_date',
        'content',
        'status',
        'created_by',
        'updated_by',
        'unit_by'
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'code' => 'Mã',
            'name' => 'Tên',
            'start_date' => 'Ngày bắt đầu',
            'end_date' => 'Ngày kết thúc',
            'content' => 'Nội dung',
            'status' => 'Trạng thái',
            'created_by' => trans('lageneral.creator'),
            'updated_by' => trans('lageneral.editor'),
        ];
    }
}
