<?php

namespace Modules\ReportNew\Entities;

use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ReportNew\Entities\BC15
 *
 * @property int $user_id
 * @property int $title_id
 * @property string|null $profile_code
 * @property string|null $full_name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $area
 * @property string|null $unit1_code
 * @property string|null $unit1_name
 * @property string|null $unit2_code
 * @property string|null $unit2_name
 * @property string|null $unit3_code
 * @property string|null $unit3_name
 * @property string|null $position
 * @property string|null $title
 * @property string|null $join_company
 * @property string|null $status
 * @property int $status_id
 * @property string|null $subject
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 query()
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 whereArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 whereJoinCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 whereProfileCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 whereUnit1Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 whereUnit1Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 whereUnit2Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 whereUnit2Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 whereUnit3Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 whereUnit3Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC15 whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BC15 extends Model
{
    protected $table = 'el_report_bc15';
    protected $primaryKey = 'user_id';
    protected $fillable=[
      'user_id',
      'title_id',
      'profile_code',
      'full_name',
      'email',
      'phone',
      'area',
      'unit1_code',
      'unit1_name',
      'unit2_code',
      'unit2_name',
      'unit2_name',
      'unit3_code',
      'unit3_name',
      'position',
      'title',
      'join_company',
      'status',
      'status_id',
      'subject',
      'mark',
    ];
    public static function sql($title_id,$status_id, $from_date, $to_date)
    {
        BC15::addGlobalScope(new DraftScope('user_id'));
        $query = \DB::table('el_report_bc15')
            ->select('*');
        $query->where('title_id', $title_id);
        if ($from_date){
            $query->where('join_company', '>=', date_convert($from_date));
        }
        if ($to_date){
            $query->where('join_company', '<=', date_convert($to_date));
        }
//        if ($title_id){
//            $query->whereIn('title_id', explode(',', $title_id));
//        }
        if ($status_id){
            $query->where('status_id', '=', $status_id);
        }
        return $query;
    }

}
