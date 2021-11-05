<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Modules\Quiz\Entities\QuizUserSecondary;

/**
 * App\Automail
 *
 * @property int $id
 * @property string $list_mail
 * @property string $params
 * @property string $template_code
 * @property int $object_id
 * @property string $object_type
 * @property int $limited
 * @property int $priority
 * @property int $sendtime
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Automail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Automail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Automail query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Automail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Automail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Automail whereLimited($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Automail whereListMail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Automail whereObjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Automail whereObjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Automail whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Automail wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Automail whereSendtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Automail whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Automail whereTemplateCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Automail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Automail extends BaseModel
{
    public $users;
    public $user_type;
    public $check_exists = false;
    public $check_exists_status = false;
    protected $table = 'el_automail';
    protected $primaryKey = 'id';
    protected $fillable = [
        'template_code',
        'object_id',
        'object_type',
        'error',
        'status',
    ];

    public function addToAutomail()
    {
        $template = MailTemplate::where('code', '=', $this->template_code)->first();
        if (empty($template)) {
            return false;
        }

        if (empty($this->users)) {
            return false;
        }
        if ($this->user_type==2)
            $email = QuizUserSecondary::where('id',$this->users)->value('email');
        else
            $email = Profile::whereIn('user_id', $this->users)->value('email');

        $automail = Automail::firstOrNew([
            'template_code' => $this->template_code,
            'object_id' => $this->object_id,
            'object_type' => $this->object_type,
            'limited' => 0,
            'status' => 0,
            'list_mail'=>$email
        ]);
        $automail->template_code = $this->template_code;
        $automail->object_id = $this->object_id;
        $automail->object_type = $this->object_type;
        $automail->params = json_encode($this->params);
        $automail->sendtime = empty($this->sendtime) ? date('Y-m-d H:i:s') : $this->sendtime;
//        $automail->list_mail = '';

        $automail->list_mail = $email;
//        if ($this->check_exists) {
//            foreach ($objects as $object) {
//                if (!$this->checkExists($object)) {
//                    $automail->list_mail .= ($automail->list_mail ? ',' : '') . $object;
//                }
//            }
//        }
//        else {
//            $automail->list_mail .= ($automail->list_mail ? ',' : '') . implode(',', $objects);
//        }
        if ($automail->list_mail) {
            return $automail->save();
        }

        return false;
    }

    public function checkExists($email) {
        $query = Automail::query();
        $query->where('template_code', '=', $this->template_code)
            ->where('object_id', '=', $this->object_id)
            ->where('object_type', '=', $this->object_type)
            ->where('list_mail', '=', $email );

        if ($this->check_exists_status != false) {
            $query->where('status', '=', $this->check_exists_status);
        }

        return $query->exists();
    }

}
