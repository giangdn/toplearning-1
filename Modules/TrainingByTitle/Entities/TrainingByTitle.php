<?php

namespace Modules\TrainingByTitle\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\TrainingByTitle\Entities\TrainingByTitle
 *
 * @property int $id
 * @property int $title_id
 * @property string $title_name
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Categories\Titles|null $title
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\TrainingByTitle\Entities\TrainingByTitleCategory[] $trainingtitlecategory
 * @property-read int|null $trainingtitlecategory_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\TrainingByTitle\Entities\TrainingByTitleDetail[] $trainingtitledetail
 * @property-read int|null $trainingtitledetail_count
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitle query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitle whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitle whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitle whereTitleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingByTitle whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TrainingByTitle extends Model
{
    protected $table = 'el_training_by_title';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title_id',
        'title_name',
        'image',
    ];
    public function trainingtitlecategory() {
        return $this->hasMany('Modules\TrainingByTitle\Entities\TrainingByTitleCategory', 'training_title_id', 'id');
    }
    public function trainingtitledetail() {
        return $this->hasMany('Modules\TrainingByTitle\Entities\TrainingByTitleDetail', 'training_title_id', 'id');
    }
    public function title() {
        return $this->hasOne('App\Models\Categories\Titles', 'id', 'title_id');
    }

}
