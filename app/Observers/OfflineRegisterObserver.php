<?php

namespace App\Observers;

use App\CourseRegisterView;
use App\ProfileView;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineRegisterView;
use Modules\ReportNew\Entities\ReportNewExportBC11;

class OfflineRegisterObserver extends BaseObserver
{
    /**
     * Handle the offline register "created" event.
     *
     * @param  \App\OfflineRegister  $offlineRegister
     * @return void
     */
    public function created(OfflineRegister $offlineRegister)
    {
        $this->syncRegisterView($offlineRegister);
        $courseName = OfflineCourse::find($offlineRegister->course_id)->name;
        $student = ProfileView::find($offlineRegister->user_id)->full_name;
        $action = 'Thêm ghi danh '.$student.' (khóa học tập trung)';
        parent::saveHistory($offlineRegister,'Insert',$action,$courseName, $offlineRegister->course_id,app(OfflineCourse::class)->getTable());

        $report_11 = ReportNewExportBC11::query()->where('course_id', '=', $offlineRegister->course_id)->where('course_type', '=', 2);
        if ($report_11->exists()){
            $total_register = OfflineRegister::whereCourseId($offlineRegister->course_id)->count();

            $report_11->update([
                'total_register' => $total_register,
            ]);
        }
    }

    /**
     * Handle the offline register "updated" event.
     *
     * @param  \App\OfflineRegister  $offlineRegister
     * @return void
     */
    public function updated(OfflineRegister $offlineRegister)
    {
        $this->syncRegisterView($offlineRegister);
        $courseName = OfflineCourse::find($offlineRegister->course_id)->name;
        $student = ProfileView::find($offlineRegister->user_id)->full_name;
        $action = 'Cập nhật ghi danh '.$student.' (khóa học tập trung)';
        if ($offlineRegister->isDirty('approved_step')){
            $action = 'Phê duyệt ghi danh '.$student.' (khóa học tập trung)';
        }
        parent::saveHistory($offlineRegister,'Update',$action,$courseName, $offlineRegister->course_id,app(OfflineCourse::class)->getTable());

        $report_11 = ReportNewExportBC11::query()->where('course_id', '=', $offlineRegister->course_id)->where('course_type', '=', 2);
        if ($report_11->exists()){
            $total_register = OfflineRegister::whereCourseId($offlineRegister->course_id)->count();

            $report_11->update([
                'total_register' => $total_register,
            ]);
        }
    }

    /**
     * Handle the offline register "deleted" event.
     *
     * @param  \App\OfflineRegister  $offlineRegister
     * @return void
     */
    public function deleted(OfflineRegister $offlineRegister)
    {
        OfflineRegisterView::destroy($offlineRegister->id);
        $courseName = OfflineCourse::find($offlineRegister->course_id)->name;
        $student = ProfileView::find($offlineRegister->user_id)->full_name;
        parent::saveHistory($offlineRegister,'Delete','Xóa ghi danh '.$student.' (khóa học tập trung)',$courseName, $offlineRegister->course_id,app(OfflineCourse::class)->getTable());

        $report_11 = ReportNewExportBC11::query()->where('course_id', '=', $offlineRegister->course_id)->where('course_type', '=', 2);
        if ($report_11->exists()){
            $total_register = OfflineRegister::whereCourseId($offlineRegister->course_id)->count();

            $report_11->update([
                'total_register' => $total_register,
            ]);
        }
    }

    /**
     * Handle the offline register "restored" event.
     *
     * @param  \App\OfflineRegister  $offlineRegister
     * @return void
     */
    public function restored(OfflineRegister $offlineRegister)
    {
        //
    }

    /**
     * Handle the offline register "force deleted" event.
     *
     * @param  \App\OfflineRegister  $offlineRegister
     * @return void
     */
    public function forceDeleted(OfflineRegister $offlineRegister)
    {
        //
    }
    private function syncRegisterView(OfflineRegister $offlineRegister){
        $model = OfflineRegisterView::firstOrNew(['id'=>$offlineRegister->id]);
        $offRegister = OfflineRegister::find($offlineRegister->id)->toArray();
        $model->fill($offRegister);
        $profile = ProfileView::where(['user_id'=>$offlineRegister->user_id])->first();
        $model->code=$profile->code;
        $model->full_name=$profile->full_name;
        $model->email=$profile->email;

        $model->title_id=$profile->title_id;
        $model->title_code=$profile->title_code;
        $model->title_name=$profile->title_name;

        $model->position_id=$profile->position_id;
        $model->position_code=$profile->position_code;
        $model->position_name=$profile->position_name;

        $model->unit_id=$profile->unit_id;
        $model->unit_code=$profile->unit_code;
        $model->unit_name=$profile->unit_name;

        $model->parent_unit_id=$profile->parent_unit_id;
        $model->parent_unit_code=$profile->parent_unit_code;
        $model->parent_unit_name=$profile->parent_unit_name;

        if ($offlineRegister->status>=0){
            $model->approved_by_2 = $model->updated_by;
            $model->approved_date_2 = $model->updated_at;
            $model->status_level_2 = $model->status;
        }
        $model->save();
        $courseRegisterView = CourseRegisterView::firstOrNew(['user_id'=>$offlineRegister->user_id,'course_id'=>$offlineRegister->course_id,'course_type'=>2]);
        $courseRegisterView->fill($model->toArray());
        $courseRegisterView->register_id=$model->id;
        $courseRegisterView->course_type=2;
        $courseRegisterView->save();
    }
}
