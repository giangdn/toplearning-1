<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

class QuizPermissionTeacher extends Model
{
    protected $table = 'el_quiz_permission_teacher';
    protected $primaryKey = 'id';
    protected $fillable = [
        'quiz_id',
        'teacher_id',
        'question_id',
    ];
}
