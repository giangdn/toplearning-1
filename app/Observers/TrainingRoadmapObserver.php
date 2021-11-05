<?php

namespace App\Observers;


use App\Models\Categories\Titles;
use App\Models\Categories\TrainingProgram;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;

class TrainingRoadmapObserver extends BaseObserver
{
    /**
     * Handle the training roadmap "created" event.
     *
     * @param  \App\TrainingRoadmap  $trainingRoadmap
     * @return void
     */
    public function created(TrainingRoadmap $trainingRoadmap)
    {
        $training_program = TrainingProgram::find($trainingRoadmap->training_program_id);
        $title = Titles::find($trainingRoadmap->title_id)->name;
        $trainingProgram = @$training_program->name;
        $action = 'Thêm tháp đào tạo '.$trainingProgram;
        parent::saveHistory($trainingRoadmap,'Insert',$action,$title);
    }

    /**
     * Handle the training roadmap "updated" event.
     *
     * @param  \App\TrainingRoadmap  $trainingRoadmap
     * @return void
     */
    public function updated(TrainingRoadmap $trainingRoadmap)
    {
        $title = Titles::find($trainingRoadmap->title_id)->name;
        $trainingProgram = TrainingProgram::find($trainingRoadmap->training_program_id)->name;
        $action = 'Cập nhật tháp đào tạo '.$trainingProgram;
        parent::saveHistory($trainingRoadmap,'Update',$action,$title);
    }

    /**
     * Handle the training roadmap "deleted" event.
     *
     * @param  \App\TrainingRoadmap  $trainingRoadmap
     * @return void
     */
    public function deleted(TrainingRoadmap $trainingRoadmap)
    {
        $training_program = TrainingProgram::find($trainingRoadmap->training_program_id);
        $title = Titles::find($trainingRoadmap->title_id)->name;
        $trainingProgram = @$training_program->name;
        $action = 'Xóa tháp đào tạo '.$trainingProgram;
        parent::saveHistory($trainingRoadmap,'Delete',$action,$title);
    }

    /**
     * Handle the training roadmap "restored" event.
     *
     * @param  \App\TrainingRoadmap  $trainingRoadmap
     * @return void
     */
    public function restored(TrainingRoadmap $trainingRoadmap)
    {
        //
    }

    /**
     * Handle the training roadmap "force deleted" event.
     *
     * @param  \App\TrainingRoadmap  $trainingRoadmap
     * @return void
     */
    public function forceDeleted(TrainingRoadmap $trainingRoadmap)
    {
        //
    }
}
