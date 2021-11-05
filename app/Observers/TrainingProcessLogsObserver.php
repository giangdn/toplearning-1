<?php

namespace App\Observers;


use Modules\User\Entities\TrainingProcess;
use Modules\User\Entities\TrainingProcessLogs;

class TrainingProcessLogsObserver extends BaseObserver
{
    /**
     * Handle the training process logs "created" event.
     *
     * @param  \App\TrainingProcessLogs  $trainingProcessLogs
     * @return void
     */
    public function created(TrainingProcessLogs $trainingProcessLogs)
    {
        $trainingProcess = TrainingProcess::find($trainingProcessLogs->process_id);
        if (in_array($trainingProcessLogs->type,[3,4])) {
            $action = $trainingProcessLogs->action;
            $note = $this->getModel($trainingProcessLogs->type);
            parent::saveHistory($trainingProcess, 'Insert', $action, $note);
        }
    }
    private function getModel($type){
        if ($type==1)
            return 'Gộp chuyên đề';
        elseif ($type==2)
            return 'Tách chuyên đề';
        elseif ($type==3)
            return 'Hoàn thành quá trình đào tạo';
        elseif ($type==4)
            return 'Chuyển quá trình đào tạo';
        return '';
    }
    /**
     * Handle the training process logs "updated" event.
     *
     * @param  \App\TrainingProcessLogs  $trainingProcessLogs
     * @return void
     */
    public function updated(TrainingProcessLogs $trainingProcessLogs)
    {
        //
    }

    /**
     * Handle the training process logs "deleted" event.
     *
     * @param  \App\TrainingProcessLogs  $trainingProcessLogs
     * @return void
     */
    public function deleted(TrainingProcessLogs $trainingProcessLogs)
    {
        //
    }

    /**
     * Handle the training process logs "restored" event.
     *
     * @param  \App\TrainingProcessLogs  $trainingProcessLogs
     * @return void
     */
    public function restored(TrainingProcessLogs $trainingProcessLogs)
    {
        //
    }

    /**
     * Handle the training process logs "force deleted" event.
     *
     * @param  \App\TrainingProcessLogs  $trainingProcessLogs
     * @return void
     */
    public function forceDeleted(TrainingProcessLogs $trainingProcessLogs)
    {
        //
    }
}
