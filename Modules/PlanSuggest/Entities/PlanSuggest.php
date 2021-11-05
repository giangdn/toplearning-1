<?php

namespace Modules\PlanSuggest\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class PlanSuggest extends BaseModel
{
    protected $table = 'el_plan_suggest';
    protected $fillable = [
        'intend',
        'subject_name',
        'purpose',
        'duration',
        'title',
        'amount',
        'teacher',
        'attach',
        'attach_report',
        'students',
        'note',
        'unit_code',
        'created_by',
        'approved_by',
        'status',
        'content',
        'type',
        'training_form',
        'start_date',
        'end_date',
        'address',
        'cost',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'start_date' => 'Thời gian bắt đầu',
            'end_date' => 'Thời gian kết thúc',
            'intend' => 'Thời gian dự kiến',
            'subject_name' => 'Tên học phần',
            'purpose' => 'Mục tiêu đào tạo',
            'duration' => 'Thời lượng',
            'title' => 'Đối tượng học',
            'amount' => 'Số lượng học viên',
            'teacher' => 'Giảng viên',
            'attach' => 'File đính kèm',
            'attach_report' => 'File đính kèm báo cáo',
            'students' => 'Danh sách học viên',
            'note' => 'Ghi chú',
            'unit_code'=> 'Mã đơn vị',
            'created_by'=> 'Người đề xuất',
            'approved_by'=> 'Người duyệt',
            'status'=> 'Trạng thái',
            'content' => 'Nội dung',
            'type' => 'Hình thức',
        ];
    }
}
