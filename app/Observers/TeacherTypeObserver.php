<?php

namespace App\Observers;

use App\Models\Categories\TeacherType;

class TeacherTypeObserver extends BaseObserver
{
    /**
     * Handle the teacher type "created" event.
     *
     * @param  \App\TeacherType  $teacherType
     * @return void
     */
    public function created(TeacherType $teacherType)
    {
        //
    }

    /**
     * Handle the teacher type "updated" event.
     *
     * @param  \App\TeacherType  $teacherType
     * @return void
     */
    public function updated(TeacherType $teacherType)
    {
        if ($teacherType->isDirty(['code','name']))
            $this->updateHasChange($teacherType,1);
    }

    /**
     * Handle the teacher type "deleted" event.
     *
     * @param  \App\TeacherType  $teacherType
     * @return void
     */
    public function deleted(TeacherType $teacherType)
    {
        $this->updateHasChange($teacherType,2);
    }

    /**
     * Handle the teacher type "restored" event.
     *
     * @param  \App\TeacherType  $teacherType
     * @return void
     */
    public function restored(TeacherType $teacherType)
    {
        //
    }

    /**
     * Handle the teacher type "force deleted" event.
     *
     * @param  \App\TeacherType  $teacherType
     * @return void
     */
    public function forceDeleted(TeacherType $teacherType)
    {
        //
    }
}
