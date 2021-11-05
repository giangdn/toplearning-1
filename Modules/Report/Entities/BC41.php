<?php

namespace Modules\Report\Entities;

use App\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BC41 extends Model
{
    public static function sql($title_id)
    {
        $query = Profile::query();
        $query->select([
            'a.user_id',
            'b.name as title_name',
        ]);
        $query->from('el_profile as a');
        $query->leftJoin('el_titles as b', 'b.code', '=', 'a.title_code');
        $query->where('b.id', '=', $title_id);

        return $query;
    }

}
