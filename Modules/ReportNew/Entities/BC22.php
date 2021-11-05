<?php

namespace Modules\ReportNew\Entities;

use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;


/**
 * Modules\ReportNew\Entities\BC22
 *
 * @property int $id
 * @property string|null $subject_merge_code mã chuyên đề gộp
 * @property string|null $subject_merge_name Tên chuyên đề gộp
 * @property string|null $subject_merges những chuyên đề cần gộp
 * @property string|null $subject_splits những chuyên đề đã tách
 * @property string|null $subject_split_code mã chuyên đề tách
 * @property string|null $subject_split_name tên chuyên đề tách
 * @property int $type 1: merge, 2: split
 * @property string|null $date_action Ngày gộp/tách
 * @property int $user_id
 * @property string|null $user_code Mã nhân viên
 * @property string|null $full_name Họ tên
 * @property string|null $email Email
 * @property string|null $phone phone
 * @property string|null $area_code mã khu vực
 * @property string|null $area_name Tên khu vực
 * @property string|null $unit1_code Mã đơn vị 1
 * @property string|null $unit1_name Tên đơn vị 1
 * @property string|null $unit2_code Tên đơn vị 2
 * @property string|null $unit2_name Tên đơn vị 2
 * @property string|null $unit3_code Tên đơn vị 3
 * @property string|null $unit3_name Tên đơn vị 3
 * @property string|null $title_code Chức danh
 * @property string|null $title_name Tên Chức danh
 * @property string|null $position_code mã Chức vụ
 * @property string|null $position_name Tên Chức vụ
 * @property string|null $note Ghi chú
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 query()
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereAreaCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereAreaName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereDateAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 wherePositionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 wherePositionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereSubjectMergeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereSubjectMergeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereSubjectMerges($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereSubjectSplitCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereSubjectSplitName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereSubjectSplits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereTitleCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereTitleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereUnit1Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereUnit1Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereUnit2Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereUnit2Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereUnit3Code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereUnit3Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereUserCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BC22 whereUserId($value)
 * @mixin \Eloquent
 */
class BC22 extends Model
{
    protected $table='el_report_bc22';
    protected $fillable=[
      'id',
      'subject_merge_code',
      'subject_merge_name',
      'subject_merges',
      'subject_splits',
      'subject_split_code',
      'subject_split_name',
      'type',
      'date_action',
      'user_id',
      'user_code',
      'full_name',
      'email',
      'phone',
      'area_code',
      'area_name',
      'unit1_code',
      'unit1_name',
      'unit2_code',
      'unit2_name',
      'unit3_code',
      'unit3_name',
      'title_code',
      'title_name',
      'position_code',
      'position_name',
      'note',
    ];
    public static function sql($type,$start_date,$end_date)
    {
        BC22::addGlobalScope(new DraftScope('user_id'));
        $query = BC22::query();
        $query->where(['type'=>$type]);
        if ($start_date && $end_date) {
            $query->where('date_action', '>=', date_convert($start_date));
            $query->where('date_action', '<=', date_convert($end_date));
        }
        if ($type==1) // merge
            $query->select('id','subject_merges as subject_merges_splits','subject_merge_code as subject_merge_split_code','subject_merge_name as subject_merge_split_name',
                'user_code','full_name','email','phone','area_code','area_name','unit1_code','unit1_name','unit2_code','unit2_name','unit3_code','unit3_name',
                'title_name','position_name','note', 'full_name as created_user','date_action');
        else // split
            $query->select('id','subject_splits as subject_merges_splits','subject_split_code as subject_merge_split_code','subject_split_name as subject_merge_split_name',
                'user_code','full_name','email','phone','area_code','area_name','unit1_code','unit1_name','unit2_code','unit2_name','unit3_code','unit3_name',
                'title_name','position_name','note', 'full_name as created_user','date_action');
        return $query;
    }
}
