<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\DailyTraining\Entities\DailyTrainingHistoryScore;
use Modules\DailyTraining\Entities\DailyTrainingUserCommentVideo;
use Modules\DailyTraining\Entities\DailyTrainingUserLikeVideo;
use Modules\DailyTraining\Entities\DailyTrainingVideo;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Promotion\Entities\PromotionUserHistory;

class ScoreByVideo extends Command
{
    protected $signature = 'command:score_video';

    protected $description = 'Điểm từ video chạy mỗi giờ (0 * * * *)';
    protected $expression ='0 * * * *';
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $videos = DailyTrainingVideo::where('status', '=', 1)->where('approve', '=', 1)->get();

        foreach ($videos as $video){
            $score = 0;

            $score_view = DailyTrainingVideo::getScoreView($video->view);
            $score_like = DailyTrainingVideo::getScoreLike($video->id);
            $score_comment = DailyTrainingVideo::getScoreComment($video->id);

            if ($score_view){
                $score += $score_view;
            }
            if ($score_like){
                $score += $score_like;
            }
            if ($score_comment){
                $score += $score_comment;
            }
            
            $check = DailyTrainingHistoryScore::query()->where('user_id', '=', $video->created_by)
            ->where('video_id', '=', $video->id);
            if ($check->exists()){
                $history = DailyTrainingHistoryScore::query()
                    ->where('user_id', '=', $video->created_by)
                    ->where('video_id', '=', $video->id)
                    ->orderByDesc('id')
                    ->first();
                $user_point = PromotionUserPoint::firstOrNew(['user_id' => $video->created_by]);
                $user_point->point = ($user_point->point - $history->score) + $score;
                $user_point->level_id = PromotionLevel::levelUp($user_point->point, $video->created_by);
                $user_point->save();
                $this->savePointHistory($history->user_id, $history->video_id, $history->score);
            }else{
                $user_point = PromotionUserPoint::firstOrNew(['user_id' => $video->created_by]);
                $user_point->point = $user_point->point + $score;
                $user_point->level_id = PromotionLevel::levelUp($user_point->point, $video->created_by);
                $user_point->save();
                $this->savePointHistory($video->created_by, $video->id, $score);
            }

            $points_history = new DailyTrainingHistoryScore();
            $points_history->user_id = $video->created_by;
            $points_history->video_id = $video->id;
            $points_history->score = $score;
            $points_history->save();
        }
    }

    private function levelUp($point)
    {
        $level = PromotionLevel::query()->where('point','<=',$point);

        if($level->exists())
            return $level->max('level');
        else
            return 0;
    }

    private function savePointHistory($user, $video_id, $point)
    {
        $user_points_history = PromotionUserHistory::firstOrNew(['user_id' => $user, 'video_id' => $video_id]);
        $user_points_history->user_id = $user;
        $user_points_history->point = $point;
        $user_points_history->daily_training = 1;
        $user_points_history->video_id = $video_id;
        $user_points_history->save();
    }
}
