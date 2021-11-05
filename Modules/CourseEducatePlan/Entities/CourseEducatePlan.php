<?php

namespace Modules\CourseEducatePlan\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class CourseEducatePlan extends BaseModel
{
    protected $primaryKey = 'id';
    protected $table = 'el_course_educate_plan';
    protected $fillable = [
        'course_type',
        'code',
        'name',
        'auto',
        'unit_id',
        'moodlecourseid',
        'isopen',
        'image',
        'start_date',
        'end_date',
        'created_by',
        'updated_by',
        'category_id',
        'description',
        'training_program_id',
        'level_subject_id',
        'subject_id',
        'plan_detail_id',
        'in_plan',
        'training_form_id',
        'register_deadline',
        'content',
        'document',
        'course_time',
        'num_lesson',
        'status',
        'views',
        'action_plan',
        'plan_app_template',
        'plan_app_day',
        'cert_code',
        'has_cert',
        'rating',
        'template_id',
        'unit_by',
        'max_student',
        'training_location_id',
        'training_unit',
        'training_area_id',
        'training_partner_id',
        'teacher_id',
        'commit',
        'commit_date',
        'coefficient',
        'cost_class',
        'quiz_id',
        'approved_by',
        'time_approved',
        'status_convert',
    ];
}
