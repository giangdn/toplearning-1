<?php

namespace App\Observers;

use App\Models\Categories\Subject;
use Modules\MergeSubject\Entities\MergeSubject;

class MergeSubjectObserver extends BaseObserver
{
    /**
     * Handle the merge subject "created" event.
     *
     * @param  \App\MergeSubject  $mergeSubject
     * @return void
     */
    public function created(MergeSubject $mergeSubject)
    {
        $subject = Subject::find($mergeSubject->subject_new)->name;
        $type = $mergeSubject->type==1?'gộp chuyên đề':'tách chuyên đề';
        $action = "Thêm ".$type;
        parent::saveHistory($mergeSubject,'Insert',$action,$subject);
    }

    /**
     * Handle the merge subject "updated" event.
     *
     * @param  \App\MergeSubject  $mergeSubject
     * @return void
     */
    public function updated(MergeSubject $mergeSubject)
    {
        $subject = Subject::find($mergeSubject->subject_new)->name;
        $type = $mergeSubject->type==1?'gộp chuyên đề':'tách chuyên đề';
        $action = "Cập nhật ".$type;
        if ($mergeSubject->isDirty('status'))
            $action = "Phê duyệt ".$type;
        parent::saveHistory($mergeSubject,'Update',$action,$subject);
    }

    /**
     * Handle the merge subject "deleted" event.
     *
     * @param  \App\MergeSubject  $mergeSubject
     * @return void
     */
    public function deleted(MergeSubject $mergeSubject)
    {
        $subject = Subject::find($mergeSubject->subject_new)->name;
        $type = $mergeSubject->type==1?'gộp chuyên đề':'tách chuyên đề';
        $action = "Xóa ".$type;
        parent::saveHistory($mergeSubject,'Delete',$action,$subject);
    }

    /**
     * Handle the merge subject "restored" event.
     *
     * @param  \App\MergeSubject  $mergeSubject
     * @return void
     */
    public function restored(MergeSubject $mergeSubject)
    {
        //
    }

    /**
     * Handle the merge subject "force deleted" event.
     *
     * @param  \App\MergeSubject  $mergeSubject
     * @return void
     */
    public function forceDeleted(MergeSubject $mergeSubject)
    {
        //
    }
}
