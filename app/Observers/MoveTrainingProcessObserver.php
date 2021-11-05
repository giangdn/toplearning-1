<?php

namespace App\Observers;

use App\Models\Categories\Subject;
use App\ProfileView;
use Modules\MoveTrainingProcess\Entities\MoveTrainingProcess;

class MoveTrainingProcessObserver extends BaseObserver
{
    /**
     * Handle the move training process "created" event.
     *
     * @param  \App\MoveTrainingProcess  $moveTrainingProcess
     * @return void
     */
    public function created(MoveTrainingProcess $moveTrainingProcess)
    {

    }

    /**
     * Handle the move training process "updated" event.
     *
     * @param  \App\MoveTrainingProcess  $moveTrainingProcess
     * @return void
     */
    public function updated(MoveTrainingProcess $moveTrainingProcess)
    {
        //
    }

    /**
     * Handle the move training process "deleted" event.
     *
     * @param  \App\MoveTrainingProcess  $moveTrainingProcess
     * @return void
     */
    public function deleted(MoveTrainingProcess $moveTrainingProcess)
    {
        $student_old = ProfileView::find($moveTrainingProcess->employee_old)->full_name;
        $student_new = ProfileView::find($moveTrainingProcess->employee_new)->full_name;
        $action = "Xóa chuyển quá trình đào tạo nhân viên ".$student_old." sang ".$student_new;
        parent::saveHistory($moveTrainingProcess,'Delete',$action,'Xóa chuyển quá trình đào tạo');
    }

    /**
     * Handle the move training process "restored" event.
     *
     * @param  \App\MoveTrainingProcess  $moveTrainingProcess
     * @return void
     */
    public function restored(MoveTrainingProcess $moveTrainingProcess)
    {
        //
    }

    /**
     * Handle the move training process "force deleted" event.
     *
     * @param  \App\MoveTrainingProcess  $moveTrainingProcess
     * @return void
     */
    public function forceDeleted(MoveTrainingProcess $moveTrainingProcess)
    {
        //
    }
}
