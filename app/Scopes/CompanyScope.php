<?php
namespace App\Scopes;
use App\Profile;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Query\Builder as BaseBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\Quiz\Entities\QuizUserSecondary;

class CompanyScope implements Scope {

    protected $obj,$field,$user_invited;
    public function __construct($field=null,$obj=null, $user_invited=null)
    {
        $this->field=$field;
        $this->obj=$obj;
        $this->user_invited = $user_invited;
    }

    public function apply(Builder $builder, Model $model)
    {
        $user_type = getUserType();

        $table = $this->obj?? $model->getTable();
        $columnWhere = $this->field??'unit_by';
        if ($user_type == 1){
            if ($table != 'el_logo'){
                if (Auth::user()->isAdmin())
                    return true;
            }

            $company = Profile::getCompany();
        }else{
            $company = QuizUserSecondary::getCompany();
        }

        $builder->fromSub(function ($query) use ($table,$columnWhere,$company){
            $query->select("$table.*")->from($table)
                ->join('el_unit_view', 'el_unit_view.id', '=', "{$table}.{$columnWhere}")
                ->where(['el_unit_view.unit0_id'=>$company]);
        },"{$table}");
        return $this->addWithCompany($builder);
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

                $this->removeBinding($query, $bindingKey);
            }
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
    protected function addWithCompany(Builder $builder)
    {
        $builder->macro('withCompany', function(Builder $builder)
        {
            $this->remove($builder);

            return $builder;
        });
    }
    public function extend(Builder $builder)
    {
        $builder->macro('removeWithCompany', function(Builder $builder)
        {
            return $builder->withoutGlobalScope($this);
        });
    }
}
