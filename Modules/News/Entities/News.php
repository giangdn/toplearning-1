<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\Model;
use App\BaseModel;
use App\CacheModel;
use App\Traits\ChangeLogs;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use App\NewsStatistic;
use App\Models\Categories\Unit;

/**
 * Modules\News\Entities\News
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int $status
 * @property int $views
 * @property string|null $image
 * @property int|null $category_id
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\News\Entities\News newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\News\Entities\News newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\News\Entities\News query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\News\Entities\News whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\News\Entities\News whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\News\Entities\News whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\News\Entities\News whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\News\Entities\News whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\News\Entities\News whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\News\Entities\News whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\News\Entities\News whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\News\Entities\News whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\News\Entities\News whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\News\Entities\News whereViews($value)
 * @mixin \Eloquent
 * @property int $hot
 * @property int|null $user_view
 * @property string|null $view_time
 * @method static \Illuminate\Database\Eloquent\Builder|News whereHot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereUserView($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereViewTime($value)
 */
class News extends CacheModel
{
    protected $table = 'el_news';
    protected $fillable = [
        'title',
        'content',
        'description',
        'views',
        'status',
        'image',
        'date_setup_icon',
        'number_setup',
        'category_id',
        'category_parent_id',
        'created_by',
        'updated_by',
        'user_view',
        'hot',
        'hot_public',
        'view_time',
        'type',
        'like_new'
    ];

    public static function getAttributeName()
    {
        return [
            'title' => 'Tiêu đề',
            'content' => 'Nội dung',
            'description' => 'Mô tả',
            'views' => 'Lượt xem',
            'category_id' => 'Danh mục',
            'category_parent_id' => 'Danh mục cha',
            'status' => 'Trang thái',
            'created_by' => 'Ngày tạo',
            'updated_by' => 'Ngày sửa',
            'type' => 'Thể loại',
        ];
    }

    public static function updateItemViews($id)
    {
        $news = News::find($id);

        DB::table('el_news')
            ->where('id', $id)
            ->update([
                'views' => $news->views + 1,
                'user_view' => \Auth::id(),
                'view_time' => date('Y-m-d H:i:s'),
            ]);

        NewsStatistic::update_news_insert_statistic(0, $id);
    }

    public static function getViewsMax($length = 3)
    {
        $query = self::query();
        $query->orderBy('views', 'DESC');
        $query->limit($length);
        return $query->get();
    }

    public static function getNewsCategory($category_id, $current_id = 0)
    {
        $query = self::query();
        $query->where('category_id', '=', $category_id);
        $query->where('id', '!=', $current_id);
        return $query->get();
    }

    public static function getLasterNews($length = 5)
    {
        $query = self::query();
        $query->orderBy('created_at', 'DESC');
        $query->limit($length);
        return $query->get();
    }

    public static function getNewsHotLaster()
    {
        $query = self::query();
        $query->where('hot', '=', 1);
        $query->orderBy('created_at', 'DESC');
        return $query->first();
    }

    public static function getNewsHot()
    {
        $hot_laster = self::getNewsHotLaster();

        $query = self::query();
        $query->where('hot', '=', 1);
        if ($hot_laster) {
            $query->where('id', '!=', $hot_laster->id);
        }
        $query->orderBy('created_at', 'DESC');
        return $query->get();
    }

    public static function getAllByViews()
    {
        $query = self::query();
        $query->orderBy('views', 'DESC');
        return $query->paginate(10);
    }

    public static function getNewsNew()
    {
        $sub3day = now()->subDays(3);

        $query = self::query();
        $query->where('created_at', '>=', $sub3day);
        $query->orderBy('created_at', 'DESC');
        return $query->paginate(10);
    }
}
