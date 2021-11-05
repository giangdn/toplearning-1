<?php

namespace App\Observers;

use App\HasChange;
use App\ProfileView;
use Illuminate\Database\Eloquent\Model;
use Modules\ModelHistory\Entities\ModelHistory;

class BaseObserver
{
    protected function updateHasChange(Model $model,int $type){

        $model = HasChange::firstOrNew(['table_name'=>$model->getTable(),'record_id'=>$model->id,'type'=>$type]);
        $model->save();
    }
    protected function saveHistory(Model $model, $code, $action, $note=null,$parent_id=null, $parent_model=null){
        $user_id = \Auth::id()??2;
        $profile = ProfileView::find($user_id);
        $fullName = $profile?$profile->full_name:'Admin';
        $hist = new ModelHistory();
        $hist->model_id= isset($model->id)?$model->id:0;
        $hist->model=$model->getTable();
        $hist->code=$code;
        $hist->action=$action;
        $hist->note=$note??strip_tags($model->name);
        $hist->parent_id=$parent_id;
        $hist->parent_model=$parent_model;
        $hist->created_name= $fullName;
        $hist->save();
    }
}
