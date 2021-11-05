<?php

namespace Modules\Libraries\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Libraries\Entities\RegisterBook
 *
 * @property int $id
 * @property int $user_id
 * @property int $book_id
 * @property int $quantily
 * @property string $borrow_date
 * @property string $pay_date
 * @property string $register_date
 * @property int $approved
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\RegisterBook newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\RegisterBook newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\RegisterBook query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\RegisterBook whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\RegisterBook whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\RegisterBook whereBorrowDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\RegisterBook whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\RegisterBook whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\RegisterBook wherePayDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\RegisterBook whereQuantily($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\RegisterBook whereRegisterDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\RegisterBook whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\RegisterBook whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\RegisterBook whereUserId($value)
 * @mixin \Eloquent
 * @property int $quantity
 * @method static \Illuminate\Database\Eloquent\Builder|RegisterBook whereQuantity($value)
 */
class RegisterBook extends BaseModel
{
    protected $table = 'el_register_book';
    protected $fillable = [
        'user_id',
        'book_id',
        'quantily',
        'borrow_date',
        'pay_date',
        'register_date',
        'approved',
        'status'
    ];
    protected $primaryKey = 'id';
}
