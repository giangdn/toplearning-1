<?php

namespace App\Traits;

trait ChangeLogs
{
    public function save(array $options = []) {
        $type = $this->id ? 'update' : 'insert';
        $result = parent::save($options);
        $model_id = $this->{$this->primaryKey};

        if ($result) {
            $model = new \App\Models\ChangeLogs();
            $model->model = get_class($this);
            $model->data = $this->toJson();
            $model->user_id = \Auth::check() ? (get_class($this) == 'App\User') ? $this->id : \Auth::id() : 2;
            $model->model_id = $model_id;
            $model->type = $type;
            $model->save();
        }
        return $result;
    }

    public function delete() {
        $model_id = $this->id;
        $model = new \App\Models\ChangeLogs();
        $model->model = get_class($this);
        $model->data = $this->toJson();
        $model->user_id = \Auth::id();
        $model->model_id = $model_id;
        $model->type = 'delete';
        $model->save();

        return parent::delete();
    }
}
