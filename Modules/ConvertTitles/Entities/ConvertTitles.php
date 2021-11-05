<?php

namespace Modules\ConvertTitles\Entities;

use Illuminate\Database\Eloquent\Model;

class ConvertTitles extends Model
{
    protected $table = 'el_convert_titles';
    protected $fillable = [
        'user_id',
        'title_old',
        'title_id',
        'unit_id',
        'unit_receive_id',
        'start_date',
        'end_date',
        'send_date',
        'note',
        'file_reviews_unit'
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'user_id' => 'Nhân viên',
            'title_old' => 'Chức danh ban đầu',
            'title_id' => 'Chức danh chuyển đổi',
            'unit_id' => 'Đơn vị tập huấn',
            'unit_receive_id' => 'Đơn vị nhận',
            'send_date' => 'Ngày gửi đánh giá',
            'note' => 'Ghi chú',
            'start_date' => 'Ngày bắt đầu',
            'end_date' => 'Ngày kết thúc',
            'file_reviews_unit' => 'File đánh giả của trưởng đơn vị'
        ];
    }
    public static function getCourse($title_id){
        $query = self::query();
        $query->from('el_convert_titles_roadmap AS a')
            ->leftJoin('el_subject AS b', function ($sub){
                $sub->on('a.training_program_id', '=', 'b.training_program_id');
                $sub->on('a.subject_id', '=', 'b.id');
            })
            ->where('b.status', '=', 1)
            ->where('a.title_id', '=', $title_id);

        return $query->get(['a.subject_id', 'a.training_program_id', 'a.training_form', 'b.name', 'b.code']);
    }
}
