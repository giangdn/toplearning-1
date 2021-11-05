<?php

namespace Modules\PermissionApproved\Entities;

use App\BaseModel;
use App\Models\Categories\Unit;
use App\Permission;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\PermissionApproved\Entities\ApprovedProcess
 *
 * @property int $id
 * @property int $unit_id
 * @property string|null $unit_name
 * @property string $hierarchy
 * @property int|null $unit_by
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedProcess newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedProcess newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedProcess query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedProcess whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedProcess whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedProcess whereHierarchy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedProcess whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedProcess whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedProcess whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedProcess whereUnitName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedProcess whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedProcess whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class ApprovedProcess extends BaseModel
{
    protected $table = 'el_approved_process';
    protected $fillable = [
        'unit_id',
        'unit_name',
        'hierarchy',
    ];

    public static function getApprovedProcess($unit_id)
    {
        $hierarchy = Unit::getHierarchyByUnit($unit_id);
        $explode = explode('/',$hierarchy);
        $hierarchyClone = $hierarchy;
        foreach ($explode as $index => $item) {
            $sec = substr($hierarchyClone,0,strripos($hierarchyClone,'/'));
            $hierarchyClone = $sec;
            $exists = ApprovedProcess::where('hierarchy',$sec)->value('unit_id');
            if ($exists){
//                $flag = true;
                return $exists;
                break;
            }
        }
        $exists = ApprovedProcess::where('hierarchy','like',$hierarchy."%")->value('unit_id');
        if ($exists)
            return $exists;
        return false;
    }
}
