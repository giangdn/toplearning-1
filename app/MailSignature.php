<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailSignature extends BaseModel
{
    protected $table = 'el_mail_signature';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unit_id',
        'content',
    ];
}
