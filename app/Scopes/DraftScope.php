<?php
namespace App\Scopes;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\Permission;
use App\PermissionTypeUnit;
use App\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Query\Builder as BaseBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Null_;

class DraftScope implements Scope {

    protected $obj,$field,$user_invited;
    public function __construct($field=null,$obj=null, $user_invited=null)
    {
        $this->field=$field;
        $this->obj=$obj;
        $this->user_invited = $user_invited;
    }

    public function apply(Builder $builder, Model $model)
    {
        $table = $this->obj?? $model->getTable();
        $tableExtend = Permission::permissionExtend($table);
        $tablePermission = $tableExtend??$table;
        $typeView = Profile::typeView($tablePermission);
        $columnWhere = $this->field??'unit_by';
        if (Auth::user()->isAdmin())
            return;

        if (!$typeView && !Permission::isUnitManager()){ //  chưa được phân quyền
            $builder->fromSub(function ($query) use ($table,$columnWhere){
                $query->select("$table.*")->from($table)->where([$columnWhere=>\Auth::id()]);
            },"{$table}");
            return $this->addWithDrafts($builder);
        }
        $managers_unit_id = Permission::isUnitManager() ? UnitManager::getIdUnitManagedByUser(): Null;
        /*if (Permission::isUnitManager()){
            $builder = $this->draftManagerUnit($builder,$table,$columnWhere);
            return $this->addWithDrafts($builder);
        }*/
        $nameType = $typeView?\Str::lower($typeView->name):null;
        $permission_type_id = $typeView? $typeView->permission_type_id:null;
        if($typeView && $typeView->type==1){ // default
            if ($nameType=='group'){
                $unit_code = Profile::getUnitCode();
                if ($columnWhere=='unit_by')
                    $builder->fromSub(function ($query) use ($table,$unit_code,$columnWhere){
                        $query->select("$table.*")->from($table)
                            ->join('el_profile as a','a.unit_id','=',"{$table}.{$columnWhere}")
                            ->join('el_unit_view','el_unit_view.unit_code','=','a.unit_code')
                            ->where('a.unit_code',$unit_code) ;
                    },"{$table}");
                else
                    $builder->fromSub(function ($query) use ($table,$unit_code,$columnWhere){
                        $query->select("$table.*")->from($table)
                            ->join('el_profile as a','a.user_id','=',"{$table}.{$columnWhere}")
                            ->join('el_unit_view','el_unit_view.unit_code','=','a.unit_code')
                            ->where('a.unit_code',$unit_code) ;
                    },"{$table}");
            }
            elseif ($nameType=='group-child'){
                $level = Profile::getLevel();
                if ($columnWhere=='unit_by')
                    $builder->fromSub(function ($query) use ($table,$level,$columnWhere){
                        $query->select("$table.*")->from($table)
                            ->join("el_unit_view","el_unit_view.unit{$level}_id","=","{$table}.{$columnWhere}");
                    },"{$table}");
                else
                    $builder->fromSub(function ($query) use ($table,$level,$columnWhere){
                        $query->select("$table.*")->from($table)
                            ->join('el_profile as a','a.user_id','=',"{$table}.{$columnWhere}")
                            ->join("el_unit_view","el_unit_view.unit{$level}_id","=","a.unit_id");
                    },"{$table}");
            }
            elseif ($nameType=='owner'){
                $unit_id = Profile::getUnitId();
                if ($columnWhere=='unit_by')
                    $builder->fromSub(function ($query) use ($table,$columnWhere,$unit_id){
                        $query->select("$table.*")->from($table)->where(['created_by'=>\Auth::id()])->where([$columnWhere=>$unit_id]);
                    },"{$table}");
                else
                    $builder->fromSub(function ($query) use ($table,$columnWhere,$unit_id){
                        $query->select("$table.*")->from($table)->where([$columnWhere=>\Auth::id()]);
                    },"{$table}");
            }
            elseif ($nameType=='global'){
                $company = Profile::getCompany();
                if ($columnWhere=='unit_by')
                    $builder->fromSub(function ($query) use ($table, $company,$columnWhere){
                        $query->select("$table.*")->from($table)
                            ->join('el_profile as a',"{$table}.{$columnWhere}",'=','a.unit_id')
                            ->join('el_unit as b','b.id','=','a.unit_id')
                            ->whereExists(function ($queryExist) use ($company){
                                $queryExist->select('id')->from('el_unit_view')->whereColumn('id','=','b.id')->where('unit2_id','=',$company);
                            });
                    },"{$table}");
                else
                    $builder->fromSub(function ($query) use ($table, $company,$columnWhere){
                        $query->select("$table.*")->from($table)
                            ->join('el_profile as a',"{$table}.{$columnWhere}",'=','a.user_id')
                            ->join('el_unit as b','b.id','=','a.unit_id')
                            ->whereExists(function ($queryExist) use ($company){
                                $queryExist->select('id')->from('el_unit_view')->whereColumn('id','=','b.id')->where('unit2_id','=',$company);
                            });
                    },"{$table}");
            }
        }
        else{ // custom
            if ($table=='el_unit')
                $builder = $this->draftUnit($builder,$columnWhere);
            else {
                $condition = PermissionTypeUnit::conditionUnitGroup($permission_type_id);
                $unit_id = Profile::getUnitId();
                if ($columnWhere == 'unit_by'){
                    $builder->fromSub(function ($query) use ($table, $permission_type_id, $condition, $unit_id, $columnWhere, $managers_unit_id) {
                        $query->select("$table.*")->from($table)
                            ->whereExists(function ($queryExists) use ($permission_type_id,$table) { // owner unit nhom quyen
                                $queryExists->select('unit_id')->from('el_permission_type_unit')
                                    ->whereColumn(['unit_id' => "{$table}.unit_by"])
                                    ->where(['type' => 'owner']);
                                if ($permission_type_id)
                                    $queryExists->where('permission_type_id','=',$permission_type_id);
                                else
                                    $queryExists->whereRaw("1=-1");
                            })
                            ->orWhereExists(function ($queryExists) use ($condition,$table) { // parent child unit nhom quyen
                                $queryExists->select('id')->from('el_unit_view')
                                    ->whereColumn(['id' =>"{$table}.unit_by"]);
                                if ($condition)
                                    $queryExists->whereRaw($condition);
                                else
                                    $queryExists->whereRaw("1=-1");
                            })
                            ->orWhereExists(function ($queryExists) use ($unit_id,$table) { //owner unit profle
                                $queryExists->select('id')->from('el_unit_view')
                                    ->whereColumn(['id' => "{$table}.unit_by"])
                                    ->where(['id' => $unit_id]);
                            });
                        if (Permission::isUnitManager()){
                            $query->orWhereIn('unit_by',$managers_unit_id);
                        }
                        if ($this->user_invited){
                            $query->orWhereIn('id',$this->user_invited);
                        }
                    }, "{$table}");
                } else { // column user_id
                    $builder->fromSub(function ($query) use ($table, $permission_type_id, $condition, $unit_id, $columnWhere, $managers_unit_id) {
                        $query->select("$table.*")->from($table)
                            ->join('el_profile as a', 'a.user_id', '=', "{$table}.{$columnWhere}")
                            ->whereExists(function ($queryExists) use ($permission_type_id) {
                                $queryExists->select('unit_id')->from('el_permission_type_unit')
                                    ->whereColumn(['unit_id' => 'a.unit_id'])
                                    ->where(['type' => 'owner']);
                                if ($permission_type_id)
                                    $queryExists->where('permission_type_id','=',$permission_type_id);
                                else
                                    $queryExists->whereRaw("1=-1");
                            })
                            ->orWhereExists(function ($queryExists) use ($condition) {
                                $queryExists->select('id')->from('el_unit_view')
                                    ->whereColumn(['id' => 'a.unit_id']);
                                if ($condition)
                                    $queryExists->whereRaw($condition);
                                else
                                    $queryExists->whereRaw("1=-1");
                            })
                            ->orWhereExists(function ($queryExists) use ($unit_id) {
                                $queryExists->select('id')->from('el_unit_view')
                                    ->whereColumn(['id' => 'a.unit_id'])
                                    ->where(['id' => $unit_id]);
                            });
                        if (Permission::isUnitManager()){
                            $query->orWhereIn($table.'.unit_id',$managers_unit_id);
                        }
                    }, "{$table}");
                }
            }
        }
        $this->addWithDrafts($builder);
    }
    public function draftManagerUnit(Builder $builder,$table, $columnWhere)
    {
        $typeView = Profile::typeView($table);
        $permission_type_id = $typeView? $typeView->permission_type_id:null;
        $condition= $permission_type_id? PermissionTypeUnit::conditionUnitGroup($permission_type_id):null;
        $unit_code = Profile::getUnitCode();
        $managers_unit_id = UnitManager::getIdUnitManagedByUser();
        $builder->fromSub(function ( $query) use ($table,$permission_type_id,$condition,$unit_code,$columnWhere,$managers_unit_id){
            $query->select("{$table}.*")->from($table)
                ->whereExists(function ($queryExists) use ($permission_type_id,$table){
                    $queryExists->select('unit_id')->from('el_permission_type_unit')
                        ->whereColumn(['unit_id'=>"{$table}.unit_by"])
                        ->where(['type'=>'owner']);
                    if ($permission_type_id)
                        $queryExists->where('permission_type_id','=',$permission_type_id);
                    else
                        $queryExists->whereRaw("1=-1");
                })->orWhereExists(function ($queryExists) use ($condition,$table){
                    $queryExists->select('id')->from('el_unit_view')
                        ->whereColumn(['id'=>"{$table}.unit_by"]);
                    if ($condition)
                        $queryExists->whereRaw($condition);
                    else
                        $queryExists->whereRaw("1=-1");
                })->orWhereExists(function ($queryExists) use ($unit_code,$table){
                    $queryExists->select('id')->from('el_unit_view')
                        ->whereColumn(['id'=>"{$table}.unit_by"])
                        ->where(['unit_code'=>$unit_code]);
                })->orWhereIn('unit_by',$managers_unit_id);
        },"{$table}");
        return $builder;
    }
    public function draftUnit(Builder $builder,$columnWhere)
    {
        $table = 'el_unit';
        $typeView = Profile::typeView($table);
        $permission_type_id = $typeView->permission_type_id;
        $condition=PermissionTypeUnit::conditionUnitGroup($permission_type_id);
        $unit_id = Profile::getUnitId();
        $managers_unit_id = Permission::isUnitManager() ? UnitManager::getIdUnitManagedByUser(): Null;
        $builder->fromSub(function ($query) use ($table,$permission_type_id,$condition,$unit_id,$managers_unit_id){
            $query->select("el_unit.*")->from($table)
                ->whereExists(function ($queryExists) use ($permission_type_id){
                    $queryExists->select('unit_id')->from('el_permission_type_unit')
                        ->whereColumn(['unit_id'=>'el_unit.id'])
                        ->where('permission_type_id','=',$permission_type_id)
                        ->where(['type'=>'owner']);
                })
                ->orWhereExists(function ($queryExists) use ($condition){
                    $queryExists->select('id')->from('el_unit_view')
                        ->whereColumn(['id'=>'el_unit.id']);
                    if ($condition)
                        $queryExists->whereRaw($condition);
                    else
                        $queryExists->whereRaw("1=-1");
                })
                ->orWhereExists(function ($queryExists) use ($unit_id){
                    $queryExists->select('id')->from('el_unit_view')
                        ->whereColumn(['id'=>'el_unit.id'])
                        ->where(['id'=>$unit_id]);
                });
            if (Permission::isUnitManager()){
                $query->orWhereIn('id',$managers_unit_id);
            }
        },"{$table}");
        return $builder;
    }
    public function remove(Builder $builder)
    {
        $query = $builder->getQuery();

        $column = $builder->getModel()->getQualifiedPublishedColumn();

        $bindingKey = 0;

        foreach ((array) $query->wheres as $key => $where)
        {
            if ($this->isPublishedConstraint($where, $column))
            {
                $this->removeWhere($query, $key);

                // Here SoftDeletingScope simply removes the where
                // but since we use Basic where (not Null type)
                // we need to get rid of the binding as well
                $this->removeBinding($query, $bindingKey);
            }

            // Check if where is either NULL or NOT NULL type,
            // if that's the case, don't increment the key
            // since there is no binding for these types
            if ( ! in_array($where['type'], ['Null', 'NotNull'])) $bindingKey++;
        }
    }
    protected function removeWhere(BaseBuilder $query, $key)
    {
        unset($query->wheres[$key]);

        $query->wheres = array_values($query->wheres);
    }
    protected function removeBinding(BaseBuilder $query, $key)
    {
        $bindings = $query->getRawBindings()['where'];

        unset($bindings[$key]);

        $query->setBindings($bindings);
    }
    protected function isPublishedConstraint(array $where, $column)
    {
        return ($where['type'] == 'Basic' && $where['column'] == $column && $where['value'] == 1);
    }
    protected function addWithDrafts(Builder $builder)
    {
        $builder->macro('withDrafts', function(Builder $builder)
        {
            $this->remove($builder);

            return $builder;
        });
    }
    public function extend(Builder $builder)
    {
        $builder->macro('removeWithDrafts', function(Builder $builder)
        {
            return $builder->withoutGlobalScope($this);
        });
    }
}
