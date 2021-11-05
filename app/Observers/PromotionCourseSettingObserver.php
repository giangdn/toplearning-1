<?php

namespace App\Observers;


use Modules\Offline\Entities\OfflineCourse;
use Modules\Promotion\Entities\PromotionCourseSetting;

class PromotionCourseSettingObserver extends BaseObserver
{
    /**
     * Handle the promotion course setting "created" event.
     *
     * @param  \App\PromotionCourseSetting  $promotionCourseSetting
     * @return void
     */
    public function created(PromotionCourseSetting $promotionCourseSetting)
    {
        $courseName = OfflineCourse::find($promotionCourseSetting->course_id)->name;
        $course_type = $this->getTypePromotion($promotionCourseSetting->type);
        $action = 'Thêm điểm thưởng ('.$course_type.')';
        parent::saveHistory($promotionCourseSetting,'Insert',$action,$courseName, $promotionCourseSetting->course_id,app(OfflineCourse::class)->getTable());
    }
    private function getTypePromotion($type){
        if ($type==1)
            $course_type = 'khóa học online';
        elseif($type==2)
            $course_type = 'khóa học tập trung';
        else
            $course_type = 'kỳ thi';
        return $course_type;
    }
    /**
     * Handle the promotion course setting "updated" event.
     *
     * @param  \App\PromotionCourseSetting  $promotionCourseSetting
     * @return void
     */
    public function updated(PromotionCourseSetting $promotionCourseSetting)
    {
        $courseName = OfflineCourse::find($promotionCourseSetting->course_id)->name;
        $course_type = $this->getTypePromotion($promotionCourseSetting->type);
        $action = 'Cập nhật điểm thưởng ('.$course_type.')';
        parent::saveHistory($promotionCourseSetting,'Update',$action,$courseName, $promotionCourseSetting->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the promotion course setting "deleted" event.
     *
     * @param  \App\PromotionCourseSetting  $promotionCourseSetting
     * @return void
     */
    public function deleted(PromotionCourseSetting $promotionCourseSetting)
    {
        $courseName = OfflineCourse::find($promotionCourseSetting->course_id)->name;
        $course_type = $this->getTypePromotion($promotionCourseSetting->type);
        parent::saveHistory($promotionCourseSetting,'Delete','Xóa điểm thưởng ('.$course_type.')',$courseName, $promotionCourseSetting->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the promotion course setting "restored" event.
     *
     * @param  \App\PromotionCourseSetting  $promotionCourseSetting
     * @return void
     */
    public function restored(PromotionCourseSetting $promotionCourseSetting)
    {
        //
    }

    /**
     * Handle the promotion course setting "force deleted" event.
     *
     * @param  \App\PromotionCourseSetting  $promotionCourseSetting
     * @return void
     */
    public function forceDeleted(PromotionCourseSetting $promotionCourseSetting)
    {
        //
    }
}
