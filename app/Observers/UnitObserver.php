<?php

namespace App\Observers;

use App\CourseRegisterView;
use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use App\ProfileView;
use App\UnitView;

class UnitObserver extends BaseObserver
{
    /**
     * Handle the unit "created" event.
     *
     * @param  \App\Unit  $unit
     * @return void
     */
    public function created(Unit $unit)
    {
        $this->syncUnit($unit);
    }

    /**
     * Handle the unit "updated" event.
     *
     * @param  \App\Unit  $unit
     * @return void
     */
    public function updated(Unit $unit)
    {
        $this->syncUnit($unit);
        if ($unit->isDirty(['code','name','parent_code']))
            $this->updateHasChange($unit,1);
    }

    /**
     * Handle the unit "deleted" event.
     *
     * @param  \App\Unit  $unit
     * @return void
     */
    public function deleted(Unit $unit)
    {
        UnitView::destroy($unit->id);
        $this->updateHasChange($unit,2);
    }

    /**
     * Handle the unit "restored" event.
     *
     * @param  \App\Unit  $unit
     * @return void
     */
    public function restored(Unit $unit)
    {
        //
    }

    /**
     * Handle the unit "force deleted" event.
     *
     * @param  \App\Unit  $unit
     * @return void
     */
    public function forceDeleted(Unit $unit)
    {
        //
    }
    private function syncUnit(Unit $unit){
        try {
            $model = UnitView::firstOrNew(['id' => $unit->id]);
            $model->status = $unit->status;
            $model->object_id = $unit->level;
            $model->unit_code = $unit->code;
            if ($unit->area_id > 0) {
                $area = Area::find($unit->area_id);
                $model->area_id = $unit->area_id;
                $model->area_code = $area->code;
                $model->area_name = $area->name;
                $model->area_level = $area->level;
            }
            $dataField = UnitView::mapField($unit->code);
            $model->fill($dataField);

            $model->save();
        }catch (\Exception $e){
            dd($e);
        }
    }
}
