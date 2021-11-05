<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

class QuizUserReview extends Model
{
    protected $table = 'el_quiz_user_review';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'user_type',
        'user_code',
        'full_name',
        'title_id',
        'title_name',
        'unit_id',
        'unit_name',
        'quiz_id',
        'part_id',
        'content',
        'username',
        'email',
        'parent_unit_id',
        'parent_unit_name',
    ];
}
