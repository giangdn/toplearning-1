<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserContactOutside extends Model
{
    protected $table = 'el_user_contact';
    protected $primaryKey = 'id';
    protected $fillable = [
        'content',
        'title',
        'created_by',
        'updated_by',
    ];

    public static function getAttributeName() {
        return [
            'content' => 'Nội dung',
            'title' => 'Tiêu đề',
        ];
    }
}
