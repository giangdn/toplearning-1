<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MailTemplate
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $title
 * @property string $content
 * @property string|null $note
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MailTemplate extends BaseModel
{
    protected $table = 'el_mail_template';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'title',
        'content',
        'note',
        'status'
    ];
}
