<?php

namespace Modules\Online\Listeners;

use Modules\AppNotification\Helpers\AppNotification;
use Modules\Notify\Entities\Notify;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseComplete;
use Modules\Promotion\Entities\PromotionCourseSetting;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Promotion\Entities\PromotionUserHistory;
use Modules\Promotion\Entities\PromotionUserPoint;
use App\EmulationPromotion;

class UserCompletePoint
{
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * @param \Modules\Online\Events\CourseCompleted $event
     * @return void
     */
    public function handle($event)
    {
        $result = $event->result;

        $setting = PromotionCourseSetting::where('course_id', $result->course_id)
            ->where('type', 1)
            ->where('status',1)
            ->whereIn('code', ['complete', 'landmarks'])
            ->get();

        if ($result->result == 1 && $setting->count() > 0) {
            foreach ($setting as $item){
                $user_point = PromotionUserPoint::firstOrCreate([
                    'user_id' => $result->user_id
                ], [
                    'point' => 0,
                    'level_id' => 0
                ]);
                if ($item->method == 0 && $item->point){
                    if ($item->start_date && $item->end_date){
                        $course_complete = OnlineCourseComplete::query()
                            ->where('user_id', '=', $result->user_id)
                            ->where('course_id', '=', $result->course_id)
                            ->first();

                        if ($item->start_date <= $course_complete->created_at && $course_complete->created_at <= $item->end_date){
                            $user_point->point += $item->point;
                        }
                    }else{
                        $user_point->point += $item->point;
                    }

                    $user_point->level_id = PromotionLevel::levelUp($user_point->point, $result->user_id);
                    $user_point->update();

                    $this->saveHistoryPromotion($result->user_id, $item->point, $item->course_id, $item->id);
                }
                if ($item->method == 1 && $item->point){
                    if ($item->min_score <= $result->score && $result->score <= $item->max_score){
                        $history_point = PromotionUserHistory::whereUserId($result->user_id)
                            ->where('course_id', '=', $item->course_id)
                            ->where('type', '=', 2)
                            ->whereIn('promotion_course_setting_id', function ($sub) use ($result){
                                $sub->select(['id'])
                                    ->from('el_promotion_course_setting')
                                    ->where('course_id', $result->course_id)
                                    ->where('type', 1)
                                    ->where('status',1)
                                    ->where('code', '=', 'landmarks')
                                    ->pluck('id')
                                    ->toArray();
                            })
                            ->orderByDesc('created_at')
                            ->first();

                        if ($history_point){
                            $user_point->point = ($user_point->point - $history_point->point) + $item->point;
                        }else{
                            $user_point->point += $item->point;
                        }

                        $user_point->level_id = PromotionLevel::levelUp($user_point->point, $result->user_id);
                        $user_point->update();

                        $this->saveHistoryPromotion($result->user_id, $item->point, $item->course_id, $item->id);
                        $this->savePromotionEmulation($result->user_id, $item->point, $item->course_id, 1);
                    }
                }
            }
        }
    }

    private function levelUp($point)
    {
        $level = PromotionLevel::query()->where('point','<=', $point);

        if($level->exists())
            return $level->max('level');
        else
            return 0;
    }

    private function saveHistoryPromotion($user_id,$point,$course_id, $promotion_course_setting_id){
        $history = new PromotionUserHistory();
        $history->user_id = $user_id;
        $history->point = $point;
        $history->type = 1;
        $history->course_id = $course_id;
        $history->promotion_course_setting_id = $promotion_course_setting_id;
        $history->save();

        $course_name = OnlineCourse::query()->find($course_id)->name;

        $model = new Notify();
        $model->user_id = $user_id;
        $model->subject = 'Thông báo đạt điểm thưởng khoá học.';
        $model->content = 'Bạn đã đạt điểm thưởng là "'. $point .'" điểm của khoá học "'. $course_name .'"';
        $model->url = null;
        $model->created_by = 0;
        $model->save();

        $content = \Str::words(html_entity_decode(strip_tags($model->content)), 10);
        $redirect_url = route('module.notify.view', [
            'id' => $model->id,
            'type' => 1
        ]);

        $notification = new AppNotification();
        $notification->setTitle($model->subject);
        $notification->setMessage($content);
        $notification->setUrl($redirect_url);
        $notification->add($user_id);
        $notification->save();
    }

    private function savePromotionEmulation($user_id,$point,$course_id, $type){
        $history = EmulationPromotion::firstOrNew(['course_id' => $course_id, 'user_id' => $user_id, 'type' => $type]);
        $history->user_id = $user_id;
        $history->point = $point;
        $history->type = 1;
        $history->course_id = $course_id;
        $history->save();
    }
}
