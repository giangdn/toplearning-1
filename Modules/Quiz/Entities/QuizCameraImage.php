<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizCameraImage
 *
 * @property int $id
 * @property int $user_id
 * @property int $user_type
 * @property int $attempt_id
 * @property string $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Quiz\Entities\QuizAttempts|null $attempt
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|QuizCameraImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizCameraImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizCameraImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizCameraImage whereAttemptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizCameraImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizCameraImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizCameraImage wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizCameraImage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizCameraImage whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizCameraImage whereUserType($value)
 * @mixin \Eloquent
 */
class QuizCameraImage extends Model
{
    protected $table = 'el_quiz_camera_images';
    protected $fillable = [
        'user_id',
        'user_type',
        'attempt_id',
        'path'
    ];
    
    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
    
    public function attempt() {
        return $this->hasOne('Modules\Quiz\Entities\QuizAttempts', 'id', 'attempt_id');
    }
}
