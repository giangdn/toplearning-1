<?php

namespace App\Models\Categories;

use App\BaseModel;
use App\Traits\ChangeLogs;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Categories\CommitMonth
 *
 * @property int $id
 * @property int $min_cost
 * @property int|null $max_cost
 * @property int $month
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $unit_by
 * @property int|null $training_type_id
 * @property int|null $group_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMonth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMonth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMonth query()
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMonth whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMonth whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMonth whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMonth whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMonth whereMaxCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMonth whereMinCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMonth whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMonth whereTrainingTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMonth whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMonth whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitMonth whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class CommitMonth extends BaseModel
{
    protected $table = 'el_commitment';
    protected $fillable = [
        'min_cost',
        'max_cost',
        'month',
        'group_id'
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'min_cost' => 'Chi phí thấp nhất',
            'max_cost' => 'Chi phí cao nhất',
            'month' => 'Tháng',
            'titles' => 'Chức danh',
        ];
    }

    public static function getMonth($cost){
        $cost = str_replace(",","",$cost);
        $commit = self::query()
            ->where('min_cost','<=', (int) $cost)
            ->where('max_cost', '>=', (int) $cost)
            ->first();
        return $commit ? $commit->month : 0;
    }
    public static function getDayCommit($title_id,$user_cost){

        $commit = \DB::table('el_commitment_title as a')
            ->join('el_commit_group as b','a.commit_group_id','=','b.id')
            ->join('el_commitment as c','c.group_id','=','b.id')
            ->where('a.title_id',$title_id)
            ->where('c.min_cost','<=',(int)$user_cost)->where('c.max_cost','>=',(int)$user_cost)
            ->select('c.month');
//        $commit = \DB::table('el_commitment as a')->join('el_commitment_title as b','a.id','=','b.commitment_id')
//            ->select('a.month')
//            ->where('b.title_id',$title_id)->where('min_cost','<=',$user_cost)->where('max_cost','>=',$user_cost);
        $commit = $commit->first();

        return $commit ? $commit->month : 0;
    }
}
