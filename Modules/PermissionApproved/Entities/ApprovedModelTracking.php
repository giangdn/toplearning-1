<?php

namespace Modules\PermissionApproved\Entities;

use App\BaseModel;
use App\Models\Categories\Unit;
use App\Permission;
use App\Profile;
use BaconQrCode\Common\Mode;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;

/**
 * Modules\PermissionApproved\Entities\ApprovedModelTracking
 *
 * @property int $id
 * @property string $model ten table
 * @property int $model_id id table
 * @property int $level cấp độ
 * @property int|null $status trạng thái phê duyệt 1 đồng ý, 0 từ chối, null chưa duyệt
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $unit_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedModelTracking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedModelTracking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedModelTracking query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedModelTracking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedModelTracking whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedModelTracking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedModelTracking whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedModelTracking whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedModelTracking whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedModelTracking whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedModelTracking whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedModelTracking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedModelTracking whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property string|null $note
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedModelTracking whereNote($value)
 * @property int $permission_approved_hist_id
 * @property string|null $created_by_name
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedModelTracking whereCreatedByName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApprovedModelTracking wherePermissionApprovedHistId($value)
 */
class ApprovedModelTracking extends BaseModel
{
    protected $table = 'el_approved_model_tracking';
    protected $fillable = [
        'model',
        'model_id',
        'level',
        'status',
        'note',
        'created_by',
        'created_by_name',
        'updated_by',
        'unit_by',
        'permission_approved_hist_id',
    ];
    private function savePermissionApprovedHist($model_approved,$unit_id){
        $check = PermissionApproved::where(['unit_id'=>$unit_id,'model_approved'=>$model_approved,'has_change'=>1])->exists();
        if (!$check){
            return;
        }

        $model = new PermissionApprovedHist();
        $model->model_approved = $model_approved;
        $model->unit_id = $unit_id;
        if($model->save()){
            // update permission approved tracking
            $query = PermissionApproved::where(['model_approved'=>$model_approved,'unit_id'=>$unit_id])
                ->selectRaw('level, unit_id , unit_by , created_by , updated_by , model_approved, id,'.\DB::raw($model->id));
            PermissionApprovedTracking::insertUsing(['level','unit_id','unit_by','created_by','updated_by','model_approved','permission_approved_id','permission_approved_hist_id'],$query);
            $permissionApprovedTracking = PermissionApprovedTracking::where(['permission_approved_hist_id'=>$model->id])->get();
            foreach ($permissionApprovedTracking as $index => $item) {
                // insert object
                $query = PermissionApprovedObject::where(['permission_approved_id'=>$item->permission_approved_id])
                ->selectRaw('level, unit_id, unit_by, created_by, updated_by, model_approved, object_id,permission_approved_id, '.\DB::raw($model->id));
                $object = PermissionApprovedObjectTracking::insertUsing(['level','unit_id','unit_by','created_by','updated_by','model_approved','object_id','permission_approved_id','permission_approved_hist_id'],$query);
                // insert user
                $query = PermissionApprovedUser::where(['permission_approved_id'=>$item->permission_approved_id])
                    ->selectRaw('level, unit_id, unit_by, created_by, updated_by, model_approved, user_id,permission_approved_id, '.\DB::raw($model->id));
                PermissionApprovedUserTracking::insertUsing(['level','unit_id','unit_by','created_by','updated_by','model_approved','user_id','permission_approved_id','permission_approved_hist_id'],$query);
                // insert title
                $query = PermissionApprovedTitle::where(['permission_approved_id'=>$item->permission_approved_id])
                    ->selectRaw('level, unit_id, unit_by, created_by, updated_by, model_approved, title_id,permission_approved_id, '.\DB::raw($model->id));
                PermissionApprovedTitleTracking::insertUsing(['level','unit_id','unit_by','created_by','updated_by','model_approved','title_id','permission_approved_id','permission_approved_hist_id'],$query);
            }
        }
        PermissionApproved::where(['model_approved'=>$model_approved,'unit_id'=>$unit_id])->update(['has_change'=>0]);
    }
    public function updateApprovedTracking(Model $model, $model_id, $status, $note=null)
    {
        $model_approved = $model->getTable();
        $unitByModel = \DB::table($model_approved)->where('id',$model_id)->value('unit_by');
        if(!$unitByModel)
            return false;
        $unitIdApprovedProcess=ApprovedProcess::getApprovedProcess($unitByModel);
        if (!$unitIdApprovedProcess) // không tồn tại quy trình phê duyệt cùng nhánh áp dụng cho đơn vị này
        {
            $unitName = @Unit::find($unitByModel)->name;
            json_message('Không tồn tại quy trình phê duyệt áp dụng cho đơn vị '.$unitName,'error');
            return false;
        }

        $this->savePermissionApprovedHist($model_approved,$unitIdApprovedProcess);
        $permissionApproved = $this->checkPermissionApproved($model_approved,$model_id,$unitIdApprovedProcess);
        if (!is_array($permissionApproved))
            return false;
        $level = $permissionApproved['level'];
        $permission_approved_hist_id = $permissionApproved['permission_approved_hist_id'];
        $approvedTracking = ApprovedModelTracking::firstOrNew(['model_id'=>$model_id,'model'=>$model_approved,'level'=>$level]);
        $approvedTracking->model = $model_approved;
        $approvedTracking->model_id = $model_id;
        $approvedTracking->level = $level;
        $approvedTracking->status = $status;
        $approvedTracking->note = $note;
        $approvedTracking->created_by_name = Profile::fullname();
        $approvedTracking->permission_approved_hist_id = $permission_approved_hist_id;
        $approvedTracking->save();
        $maxLevel = $this->getLastLevelApproved($model_approved,$permission_approved_hist_id,$unitIdApprovedProcess);
        $_model = $model::where('id',$model_id)->first();
        $_model->approved_step = $level.'/'.$maxLevel;
        if ($maxLevel == $level)
            $_model->status = $status;
        $_model->save();
        return true;
    }
    private function getLastLevelApproved($model,$permission_approved_hist_id,$unit_id){
        return PermissionApprovedTracking::where(['unit_id'=>$unit_id, 'model_approved'=>$model,'permission_approved_hist_id'=>$permission_approved_hist_id])->max('level');
    }
    private function getLastPermissionApprovedHist($model, $unit_id){
        return PermissionApprovedHist::where(['unit_id'=>$unit_id,'model_approved'=>$model])->select('id')->orderByDesc('updated_at')->limit(1)->value('id');
    }
    private function checkPermissionApproved($model, $model_id, $unit_id){
        // check đang ở level hiện tại
        $unitByModel = \DB::table($model)->where('id',$model_id)->value('unit_by');
        $unitByUser = Profile::getUnitManagerByUser();

        $tracking =  ApprovedModelTracking::where(['model_id'=>$model_id,'model'=>$model])->select('level','status','permission_approved_hist_id')->orderByDesc('level')->limit(1)->first();
        if (!$tracking){
            $exists = PermissionApproved::where(['unit_id'=>$unit_id,'model_approved'=>$model])->exists();
            if (!$exists){
                json_message('Không tồn tại quy trình phê duyệt cho chức năng này','error');
                return false;
            }
        }
        $currentLevel = $tracking->level??1;
        if ($tracking)
            $permission_approved_hist_id = $tracking->permission_approved_hist_id;
        else
            $permission_approved_hist_id = $this->getLastPermissionApprovedHist($model,$unit_id);
        $permissionApproved = PermissionApprovedTracking::where(['unit_id'=>$unit_id, 'model_approved'=>$model,'permission_approved_hist_id'=>$permission_approved_hist_id])->select('id','level')->orderByDesc('level')->limit(1)->first();

        $lastLevel = $permissionApproved->level;
        // kiểm tra user có quyền phê duyệt phủ quyết
        $userPermissionLastLevel=$this->userPermissionLastLevel($model,$unit_id,$lastLevel,$permission_approved_hist_id,$unitByModel,$unitByUser);
        //////////////////////////////////////////////
        $isAdmin = Permission::isAdmin();
        if ($userPermissionLastLevel || $isAdmin) // user có quyền duyệt cấp cuối cùng
            $levelProcess = [$lastLevel];
        elseif ($tracking && $tracking->status) { // đã duyệt
            // xét next level
            if ($currentLevel >= $lastLevel) // level  cuối
                $levelProcess = [$currentLevel];
            else{
                $nexLevel = $currentLevel + 1;
                $levelProcess = [$currentLevel,$nexLevel];
            }
        }else
            $levelProcess = [$currentLevel];
        foreach ($levelProcess as $index => $level) {
            if ($isAdmin){
                return ['level'=>$level,'permission_approved_hist_id'=>$permission_approved_hist_id,'type'=>0];
            }
            $permissionApproved = PermissionApprovedTracking::where(['unit_id'=>$unit_id, 'model_approved'=>$model,'level'=>$level,'permission_approved_hist_id'=>$permission_approved_hist_id])->select('id','level','permission_approved_id')->first();
            // xét theo cấp duyệt

            $permissionApprovedObject = PermissionApprovedObjectTracking::where(['permission_approved_id'=>$permissionApproved->permission_approved_id,'permission_approved_hist_id'=>$permission_approved_hist_id])->value('object_id');

            if ($permissionApprovedObject==1) // cùng cấp //
            {
                $unitManager = Permission::isUnitManager();
                if(in_array( $unitByModel, $unitByUser) && $unitManager)
                    return ['level'=>$level,'permission_approved_hist_id'=>$permission_approved_hist_id,'type'=>1];
                else
                    $object = false;
            }else{ // trên cấp
                $unitManager = Permission::isUnitManager();
                $num = $permissionApprovedObject-1;
                if( Unit::isOverLevel($unitByModel,$num) && $unitManager)
                    return ['level'=>$level,'permission_approved_hist_id'=>$permission_approved_hist_id,'type'=>2];
                else
                    $object = false;
            }
            //xét theo chức danh có quyền duyệt
            $titleUser = Profile::getTItleId();
            $permissionApprovedTitle = PermissionApprovedTitleTracking::where(['permission_approved_id'=>$permissionApproved->permission_approved_id,'title_id'=>$titleUser,'permission_approved_hist_id'=>$permission_approved_hist_id])->exists();
            if($permissionApprovedTitle){
                return ['level'=>$level,'permission_approved_hist_id'=>$permission_approved_hist_id,'type'=>3];
            }else
                $title = false;
            //xét theo user có quyền duyệt
            $permissionApprovedUser = PermissionApprovedUserTracking::where(['permission_approved_id'=>$permissionApproved->permission_approved_id,'user_id'=>\Auth::id(),'permission_approved_hist_id'=>$permission_approved_hist_id])->exists();

            if ($permissionApprovedUser)
                return ['level'=>$level,'permission_approved_hist_id'=>$permission_approved_hist_id,'type'=>4];
            else
                $user = false;
            if (!$object && !$title && !$user)
                return false;
        }
        return false;
    }
    private function userPermissionLastLevel($model,$unit_id, $lastLevel,$permission_approved_hist_id, $unitByModel, $unitByUser){
        $permissionApproved = PermissionApprovedTracking::where(['unit_id'=>$unit_id, 'model_approved'=>$model,'level'=>$lastLevel,'permission_approved_hist_id'=>$permission_approved_hist_id])->select('id','level','permission_approved_id')->first();
        $permissionApprovedObject = PermissionApprovedObjectTracking::where(['permission_approved_id'=>$permissionApproved->permission_approved_id,'permission_approved_hist_id'=>$permission_approved_hist_id])->value('object_id');
        if ($permissionApprovedObject==1) // cùng cấp //
        {
            $unitManager = Permission::isUnitManager();
            if(in_array( $unitByModel, $unitByUser) && $unitManager)
                return true;
            else
                $object = false;
        }else{ // trên cấp
            $unitManager = Permission::isUnitManager();
            $num = $permissionApprovedObject-1;
            if( Unit::isOverLevel($unitByModel,$num) && $unitManager)
                return true;
            else
                $object = false;
        }
        //xét theo chức danh có quyền duyệt
        $titleUser = Profile::getTItleId();
        $permissionApprovedTitle = PermissionApprovedTitleTracking::where(['permission_approved_id'=>$permissionApproved->permission_approved_id,'title_id'=>$titleUser,'permission_approved_hist_id'=>$permission_approved_hist_id])->exists();
        if($permissionApprovedTitle){
            return true;
        }else
            $title = false;
        //xét theo user có quyền duyệt
        $permissionApprovedUser = PermissionApprovedUserTracking::where(['permission_approved_id'=>$permissionApproved->permission_approved_id,'user_id'=>\Auth::id(),'permission_approved_hist_id'=>$permission_approved_hist_id])->exists();

        if ($permissionApprovedUser)
            return true;
        else
            $user = false;
        if (!$object && !$title && !$user)
            return false;
        return false;
    }

}
