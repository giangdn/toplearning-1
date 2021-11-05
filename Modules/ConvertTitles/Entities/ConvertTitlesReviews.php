<?php

namespace Modules\ConvertTitles\Entities;

use Illuminate\Database\Eloquent\Model;

class ConvertTitlesReviews extends Model
{
    protected $table = 'el_convert_titles_reviews';
    protected $fillable = [
        'title_id',
        'file_reviews',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'title_id' => 'Chức danh chuyển đổi',
            'file_reviews' => 'Mẫu đánh giá theo chức danh',
        ];
    }
}
