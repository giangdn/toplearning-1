<?php

namespace Modules\AppNotification\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\AppNotification\Entities\AppDeviceToken
 *
 * @property int $id
 * @property int $user_id
 * @property string $device_model
 * @property string $version_code
 * @property string $device_token
 * @property int $updated
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AppDeviceToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppDeviceToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppDeviceToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|AppDeviceToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppDeviceToken whereDeviceModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppDeviceToken whereDeviceToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppDeviceToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppDeviceToken whereUpdated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppDeviceToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppDeviceToken whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppDeviceToken whereVersionCode($value)
 * @mixin \Eloquent
 */
class AppDeviceToken extends Model
{
    protected $table = 'el_app_device_tokens';
    protected $fillable = [
        'user_id',
        'device_model',
        'version_code',
        'device_token',
    ];
}
