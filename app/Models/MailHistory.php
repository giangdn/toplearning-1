<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\MailHistory
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $name
 * @property string|null $params
 * @property string|null $content
 * @property string|null $list_mail
 * @property string|null $send_time
 * @property string|null $error
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MailHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MailHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MailHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|MailHistory whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailHistory whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailHistory whereError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailHistory whereListMail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailHistory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailHistory whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailHistory whereSendTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailHistory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MailHistory extends Model
{
    protected $table = 'el_mail_history';
    protected $fillable=[
        'code',
        'name',
        'params',
        'content',
        'list_mail',
        'send_time',
        'error',
        'status',
    ];
}
