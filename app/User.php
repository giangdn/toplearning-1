<?php

namespace App;

use App\Helpers\LdapLogin;
use App\Traits\ChangeLogs;
use Carbon\Carbon;
use HighIdeas\UsersOnline\Traits\UsersOnlineTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\User
 *
 * @property int $id
 * @property string $auth
 * @property int $confirmed
 * @property int $policyagreed
 * @property int $deleted
 * @property int $suspended
 * @property int $mnethostid
 * @property string $username
 * @property string $password
 * @property string $idnumber
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property int $emailstop
 * @property string $icq
 * @property string $skype
 * @property string $yahoo
 * @property string $aim
 * @property string $msn
 * @property string $phone1
 * @property string $phone2
 * @property string $institution
 * @property string $department
 * @property string $address
 * @property string $city
 * @property string $country
 * @property string $lang
 * @property string $calendartype
 * @property string $theme
 * @property string $timezone
 * @property int $firstaccess
 * @property int $lastaccess
 * @property int $lastlogin
 * @property int $currentlogin
 * @property string $lastip
 * @property string $secret
 * @property int $picture
 * @property string $url
 * @property string|null $description
 * @property int $descriptionformat
 * @property int $mailformat
 * @property int $maildigest
 * @property int $maildisplay
 * @property int $autosubscribe
 * @property int $trackforums
 * @property int $timecreated
 * @property int $timemodified
 * @property int $trustbitmask
 * @property string|null $imagealt
 * @property string|null $lastnamephonetic
 * @property string|null $firstnamephonetic
 * @property string|null $middlename
 * @property string|null $alternatename
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAim($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAlternatename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAuth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAutosubscribe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCalendartype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereConfirmed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCurrentlogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDescriptionformat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailstop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFirstaccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFirstnamephonetic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereIcq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereIdnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereImagealt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereInstitution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastaccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastlogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastnamephonetic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereMaildigest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereMaildisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereMailformat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereMiddlename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereMnethostid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereMsn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhone1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhone2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePolicyagreed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSkype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSuspended($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTimecreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTimemodified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTrackforums($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTrustbitmask($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereYahoo($value)
 * @mixin \Eloquent
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User role($roles, $guard = null)
 * @property-read \App\Profile|null $profile
 * @property string|null $last_online
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Survey\Entities\Survey[] $surveys
 * @property-read int|null $surveys_count
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastOnline($value)
 * @property-read \App\Models\Categories\TrainingTeacher|null $teacher
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
class User extends Authenticatable
{
    use Notifiable, HasRoles, ChangeLogs, UsersOnlineTrait;

    protected $table = 'user';
    protected $fillable = [
        'username', 'email', 'confirmed', 'mnethostid','last_online', 'code'
    ];
    protected $hidden = [
        'password',
    ];

    public $timestamps = false;

    public function notifications() {
        return $this->morphMany(Notifications::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    public function profile()
    {
        return $this->hasOne('App\Profile','user_id');
    }

    public function surveys() {
        return $this->belongsToMany('Modules\Survey\Entities\Survey','el_survey_user','user_id','survey_user');
    }

    public function teacher() {
        return $this->hasOne('App\Models\Categories\TrainingTeacher', 'user_id', 'id');
    }

    public function updateAnalytics() {
        if ($this->id) {
            $analytics = Analytics::where('user_id', '=', $this->id)->where('day', '=', date('Y-m-d'))->orderBy('id', 'desc')->first();

            if ($analytics) {
                Analytics::where('id', '=', $analytics->id)->update(['end_date' => date('Y-m-d H:i:s')]);
            }
            else {
                $analytic = new Analytics();
                $analytic->user_id = $this->id;
                $analytic->ip_address = request()->ip();
                $analytic->start_date = date('Y-m-d H:i:s');
                $analytic->end_date = date('Y-m-d H:i:s');
                $analytic->day = date('Y-m-d');
                $analytic->save();
            }
        }
    }

    public function isAdmin() {

        if (in_array(Auth::user()->username,['admin','superadmin']))
            return true;
        return Auth::user()->roles()->where('name', 'Admin')->count();
    }

    public function existsRole()
    {
        return Auth::user()->roles()->count();
    }
    public function isTeacher() {
        if ($this->isAdmin()) {
            return true;
        }

        return $this->teacher()->exists();
    }

    /**
     * Check and login user.
     * @param string $password
     * @param bool $remember
     * @return bool
     * */
    public function login($password, $remember = false) {
        if ($this->auth == 'ldap') {
            $ldap = new LdapLogin();
            if ($ldap->login($this->username, $password)) {
                Auth::loginUsingId($this->id);
                return true;
            }
        }

        if ($this->auth == 'manual') {
            $auth = Auth::attempt([
                'username' => $this->username,
                'password' => $password
            ], $remember);

            if ($auth) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get profile info by user id
     * @param int $id
     * @return \App\Profile
     * */
    public static function getProfileById($id)
    {
        $user = self::find($id);
        return $user;
    }

    /**
     * Count user online.
     * @return int
     * */
    public static function countUsersOnline()
    {
        $seconds_since_last_activity = \Config::get('session.lifetime')*60;
        $last_activity = Carbon::now()->subSeconds($seconds_since_last_activity);
        $online_users = User::where('last_online', '>=', $last_activity)->count();
        return $online_users;
    }
    public static function canPermissionReport() {
        $user = Auth::user();
        return Auth::user()->roles()->where('name', 'Admin')->count() || \DB::table('el_model_has_permissions as a')->join('el_permissions as b','a.permission_id','=','b.id')
            ->where(['a.model_id'=>$user->id])->where('b.name','like','%report%')->exists() || (Auth::id() == 2);
    }
    public static function canPermissionCategoryUnit() {
        $user = Auth::user();
        return Auth::user()->roles()->where('name', 'Admin')->count() || $user->can('category-unit') || $user->can('category-unit-type') || $user->can('category-titles') || $user->can('category-cert');
    }
    public static function canPermissionCategoryCourse() {
        $user = Auth::user();
        return Auth::user()->roles()->where('name', 'Admin')->count() || $user->can('category-training-program') || $user->can('category-level-subject')
            || $user->can('category-subject') || $user->can('category-training-location') || $user->can('category-training-form');
    }
    public static function canPermissionCategoryQuiz() {
        $user = Auth::user();
        return Auth::user()->roles()->where('name', 'Admin')->count() || $user->can('category-quiz-type') ;
    }
    public static function canPermissionCategoryCost() {
        $user = Auth::user();
        return Auth::user()->roles()->where('name', 'Admin')->count() || $user->can('category-training-cost') || $user->can('category-student-cost') || $user->can('commit-month');
    }
    public static function canPermissionCategoryTeacher() {
        $user = Auth::user();
        return Auth::user()->roles()->where('name', 'Admin')->count() || $user->can('category-teacher') || $user->can('category-teacher-type') || $user->can('category-partner');
    }
    public static function canPermissionCategoryProvince() {
        $user = Auth::user();
        return Auth::user()->roles()->where('name', 'Admin')->count() || $user->can('category-province') || $user->can('category-district');
    }
    // quyền tin tức chung
    public static function canPermissionNewsGeneral() {
        $user = Auth::user();
        return Auth::user()->roles()->where('name', 'Admin')->count() || $user->can('news-outside-category') || $user->can('news-outside-list');
    }
    // quản lý đào tạo
    public static function canPermissionTrainingManager() {
        $user = Auth::user();
        return Auth::user()->roles()->where('name', 'Admin')->count() || $user->can('training-roadmap') || $user->can('training-by-title') || $user->can('training-by-title-result') || $user->can('subjectregister')
            || $user->can('indemnify') || $user->can('mergesubject') || $user->can('splitsubject') || $user->can('subjectcomplete') || $user->can('movetrainingprocess') || $user->can('certificate-template');
    }
    // Tổ chức đào tạo
    public static function canPermissionTrainingOrganization() {
        $user = Auth::user();
        return Auth::user()->roles()->where('name', 'Admin')->count() || $user->can('online-course') || $user->can('offline-course') || $user->can('training-plan') || $user->can('course-plan')
            || $user->can('course-old');
    }
    // Quiz
    public static function canPermissionQuiz() {
        $user = Auth::user();
        return Auth::user()->roles()->where('name', 'Admin')->count() || $user->can('quiz-category-question') || $user->can('quiz') || $user->can('quiz-grading') || $user->can('quiz-template')
            || $user->can('quiz-dashboard') || $user->can('quiz-setting-alert') || $user->can('quiz-user-secondary') || $user->can('quiz-history') || $user->can('quiz-history-user-second') || $user->isTeacher();
    }
}
