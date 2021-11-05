<?php

namespace Modules\Potential\Entities;

use Illuminate\Database\Eloquent\Model;

class Potential extends Model
{
    protected $table = 'el_potential';
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
    ];
    protected $primaryKey = 'id';
    public static function getAttributeName() {
        return [
            'user_id'=>'Mã nhân viên',
            'start_date'=>'Thời gian bắt đầu',
            'end_date'=>'Thời gian kết thúc',
        ];
    }

    public static function getCourse($title_id){
        $query = self::query();
        $query->from('el_potential_roadmap AS a')
            ->leftJoin('el_subject AS b', function ($sub){
                $sub->on('a.training_program_id', '=', 'b.training_program_id');
                $sub->on('a.subject_id', '=', 'b.id');
            })
            ->where('b.status', '=', 1)
            ->where('a.title_id', '=', $title_id);
        return $query->get(['a.subject_id', 'a.training_program_id', 'a.training_form', 'b.name', 'b.code']);
    }
}