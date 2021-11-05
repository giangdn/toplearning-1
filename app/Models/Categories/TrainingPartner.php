<?php

namespace App\Models\Categories;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Categories\TrainingPartner
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $people
 * @property string|null $address
 * @property string|null $email
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingPartner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingPartner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingPartner query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingPartner whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingPartner whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingPartner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingPartner whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingPartner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingPartner whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingPartner wherePeople($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingPartner wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingPartner whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TrainingPartner extends BaseModel
{
    protected $table = 'el_training_partner';
    protected $fillable = [
        'code',
        'name',
        'people',
        'address',
        'email',
        'phone'
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'code' => 'Mã',
            'name' => 'Tên đối tác',
            'people' => 'Người liên hệ',
            'address' => 'Địa chỉ',
            'email' => 'Email',
            'phone' => 'Số điện thoại'
        ];
    }
}
