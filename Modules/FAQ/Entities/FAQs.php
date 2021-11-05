<?php

namespace Modules\FAQ\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class FAQs extends BaseModel
{
    protected $table = 'el_faq';
    protected $fillable = [
        'name',
        'content',
    ];
    public static function getAttributeName() {
        return [
            'name' => 'Tiêu đề',
            'content' => 'Nội dung',
        ];
    }
    protected $primaryKey = 'id';
}
