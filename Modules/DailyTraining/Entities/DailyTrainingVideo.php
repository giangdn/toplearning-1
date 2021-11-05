<?php

namespace Modules\DailyTraining\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class DailyTrainingVideo extends BaseModel
{
    protected $table = 'el_daily_training_video';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'video',
        'hashtask',
        'category_id',
        'created_by',
        'updated_by',
        'view',
        'status',
        'approve',
    ];

    public function getLinkPlay() {
        $storage = \Storage::disk('local');
        $file = encrypt_array([
            'path' => $storage->path('uploads/' . $this->video),
        ]);

        return route('stream.video', [$file]);
    }

    public static function getAttributeName() {
        return [
            'name' => 'Tên video',
            'video' => 'Video',
            'hashtask' => 'Hashtask',
            'category_id' => 'Danh mục',
            'created_by' => trans('lageneral.creator'),
            'updated_by' => trans('lageneral.editor'),
            'view' => 'Lượt xem',
            'status' => 'Trạng thái',
            'approve' => 'Duyệt',
        ];
    }

    public static function getVideoByCategory($category_id = null)
    {
        $query = self::query();
        if ($category_id){
            $query->where('category_id', '=', $category_id);
        }
        $query->where('status', '=', 1);
        $query->where('approve', '=', 1);
        return $query->get();
    }

    public static function updateView($id){
        $model = DailyTrainingVideo::find($id);
        $model->view = $model->view + 1;
        $model->save();
    }

    public static function checkLike($video, $type){
        $query = DailyTrainingUserLikeVideo::query();
        $query->where('video_id', '=', $video);
        $query->where('user_id', '=', \Auth::id());
        if ($type == 1){
            $query->where('like', '=', 1);
        }else{
            $query->where('dislike', '=', 1);
        }

        return $query->exists();
    }

    public static function getScoreView($view){
        $setting_view = DailyTrainingSettingScoreViews::query()
            ->where('from','<=', $view)
            ->orWhere(function ($sub) use ($view){
                $sub->whereNotNull('to')
                    ->where('from','<=', $view)
                    ->where('to', '>=', $view);
            })
            ->latest()->first();

        return $setting_view ? $setting_view->score : '';
    }

    public static function getScoreComment($video_id){
        $count_comment = DailyTrainingUserCommentVideo::where('video_id', '=', $video_id)->count();

        $setting_comment = DailyTrainingSettingScoreComment::query()
            ->where('from','<=', $count_comment)
            ->orWhere(function ($sub) use ($count_comment){
                $sub->whereNotNull('to')
                    ->where('from','<=', $count_comment)
                    ->where('to', '>=', $count_comment);
            })
            ->latest()->first();

        return $setting_comment ? $setting_comment->score : '';
    }

    public static function getScoreLike($video_id){
        $count_like = DailyTrainingUserLikeVideo::where('video_id', '=', $video_id)->where('like', '=', 1)->count();

        $setting_like = DailyTrainingSettingScoreLike::query()
            ->where('from','<=', $count_like)
            ->orWhere(function ($sub) use ($count_like){
                $sub->whereNotNull('to')
                    ->where('from','<=', $count_like)
                    ->where('to', '>=', $count_like);
            })
            ->latest()->first();

        return $setting_like ? $setting_like->score : '';
    }
}
