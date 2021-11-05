<?php

namespace Modules\Quiz\Entities;

use App\BaseModel;
use App\Profile;
use App\Traits\ChangeLogs;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizRegister
 *
 * @property int $id
 * @property int $quiz_id
 * @property int $user_id
 * @property int $part_id
 * @property int $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRegister newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRegister newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRegister query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRegister whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRegister whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRegister wherePartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRegister whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRegister whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRegister whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizRegister whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Quiz\Entities\QuizPart[] $quizparts
 * @property-read int|null $quizparts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Profile[] $users
 * @property-read int|null $users_count
 */
class QuizRegister extends BaseModel
{
    use ChangeLogs;
    protected $table = 'el_quiz_register';
    protected $fillable = [
        'user_id',
        'quiz_id',
        'part_id',
        'type'
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'user_id' => 'Nhân viên',
            'quiz_id' => 'Kỳ thi',
            'part_id' => 'Ca thi',
            'type' => 'Loại thí sinh'
        ];
    }

    public static function checkExists($user_id, $quiz_id){
        $query = self::query();
        $query->where('user_id', '=', $user_id);
        $query->where('quiz_id', '=', $quiz_id);
        $query->where('type', '=', 1);
        return $query->exists();
    }

    public static function checkSecondaryExists($user_secondary_id, $quiz_id){
        $query = self::query();
        $query->where('user_id', '=', $user_secondary_id);
        $query->where('quiz_id', '=', $quiz_id);
        $query->where('type', '=', 2);
        return $query->exists();
    }
    public function users()
    {
        return $this->belongsToMany(Profile::class,'el_quiz_register','id','user_id');
    }
    public function quizparts()
    {
        return $this->belongsToMany(QuizPart::class,'el_quiz_register','id','part_id');
    }
}
