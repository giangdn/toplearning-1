<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Query\Expression;

class OverrideTablePrefixScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $prefix = \DB::getTablePrefix();
        $builder->from(new Expression($model->getTable().' as '.$prefix.$model->getTable()) );
    }
}
