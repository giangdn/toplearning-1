<?php

namespace App\Models\Categories;

use App\BaseModel;
use App\Traits\ChangeLogs;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Categories\CommitGroup
 *
 * @property int $id
 * @property string $group Nhóm
 * @property int|null $training_type_id Hình thức đào tạo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CommitGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommitGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommitGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|CommitGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitGroup whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitGroup whereTrainingTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CommitGroup extends BaseModel
{
    protected $table = 'el_commit_group';
    protected $fillable = [
        'training_type_id',
        'group'
    ];

    public static function getAttributeName() {
        return [
            'group' => 'Nhóm',
            'training_type_id' => 'Hình thức đào tạo',
        ];
    }

}
