<?php

namespace Modules\Quiz\Entities;

use App\BaseModel;
use App\Profile;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizPart
 *
 * @property int $id
 * @property int $quiz_id
 * @property string $name
 * @property string $start_date
 * @property string $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizPart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizPart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizPart query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizPart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizPart whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizPart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizPart whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizPart whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizPart whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizPart whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Quiz\Entities\QuizTeacher[] $quizTeachers
 * @property-read int|null $quiz_teachers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Profile[] $users
 * @property-read int|null $users_count
 */
class QuizPart extends BaseModel
{
    protected $table = 'el_quiz_part';
    protected $primaryKey = 'id';
    protected $fillable = [
        'quiz_id',
        'name',
        'start_date',
        'end_date',
        'created_by',
        'updated_by',
        'unit_by',
    ];

    public static function checkQuizPartOnline($quiz_id)
    {
        $user_type = Quiz::getUserType();
        $item = QuizPart::where('quiz_id', '=', $quiz_id)
            ->whereIn('id', function ($subquery) use ($user_type, $quiz_id) {
                $subquery->select(['a.part_id'])
                    ->from('el_quiz_register AS a')
                    ->join('el_quiz_part AS b', 'b.id', '=', 'a.part_id')
                    ->where('a.quiz_id', '=', $quiz_id)
                    ->where('a.user_id', '=', \Auth::id())
                    ->where('a.type', '=', $user_type)
                    ->where(function ($where){
                        $where->orWhere('b.end_date', '>', date('Y-m-d H:i:s'));
                        $where->orWhereNull('b.end_date');
                    });
            })->first();
        return $item;
    }
    public function users()
    {
        return $this->hasMany(Profile::class,'user_id','user_id');
    }

    public function quizTeachers()
    {
        return $this->hasMany(QuizTeacher::class,'quiz_id','quiz_id');
    }
}
