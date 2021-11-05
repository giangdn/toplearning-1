<?php

use App\Helpers\LaravelHooks;
use App\MailSignature;
use App\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Modules\Quiz\Entities\QuizUserSecondary;
use Intervention\Image\ImageManagerStatic;
use App\User;
use App\Permission;

function json_result($result_data) {
    header('Content-Type: application/json');
    echo json_encode($result_data);
    exit();
}

function json_message($message, $status = 'success') {
    header('Content-Type: application/json');
    echo json_encode(['status' => $status, 'message' => $message]);
    exit();
}
function json_success() {
    json_message(trans('backend.update_success'));
}
function is_url($url) {
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        return true;
    }

    return false;
}

function data_file($path, $return_path = true, $disk = 'local') {
    if (empty($path)) {
        return false;
    }

    $storage = \Storage::disk($disk);
    $file_path = $storage->path($path);

    if (file_exists($file_path) && !is_dir($file_path)) {
        if ($return_path) {
            return $file_path;
        }
        return $storage->url($path);
    }

    return null;
}

function upload_file($path) {
    $storage = \Storage::disk(config('app.datafile.upload_disk'));

    if ($storage->exists($path)) {
        return $storage->url($path);
    }

    return null;
}

function image_file($path) {
    if (is_url($path)) {
        return $path;
    }

    $data_file = upload_file($path);
    if ($data_file) {
        return $data_file;
    }

    return asset('images/image_default.jpg');
}

function image_library($path) {
    $data_file = upload_file($path);
    if ($data_file) {
        return $data_file;
    }

    return asset('images/library_default.jpg');
}

function upload_image($sizes, $image) {
    $uploadPath = data_file(path_upload($image), true, 'upload');
    $new_folder = date('Y/m/d') . '/';
    $storage = \Storage::disk('upload');
    if (!$storage->exists($new_folder)) {
        \File::makeDirectory($storage->path($new_folder), 0777, true);
    }
    list($width, $height) = $sizes;
    $file_path = $new_folder . basename($image);
    $resize_image = ImageManagerStatic::make($uploadPath);
    $resize_image->resize($width, $height);
    $resize_image->response();
    $resize_image->encode('webp');
    if (in_array('gif', explode('.', $file_path))) {
        $resize_image->destroy();
    } else {
        $resize_image->save($storage->path($file_path));
    }
    return $file_path;
}

function sub_char($string, $limit = 50, $end = '...') {
    return \Illuminate\Support\Str::words($string, $limit, $end);
}

function get_date($date, $format = "d/m/Y"){
    if(empty($date)) {
        return '';
    }
    $date = str_replace('/','-',$date);
    return date($format, strtotime($date));
}
function get_datetime($date, $format = "d/m/Y H:i:s"){
    if(empty($date)) {
        return '';
    }

    return date($format, strtotime($date));
}
function add_day($days, $format = "d/m/Y"){
    $date = date("Y-m-d");
    return date($format, strtotime($date."+ $days days"));
}

function filesize_formatted($file_size)
{
    $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $file_size > 0 ? floor(log($file_size, 1024)) : 0;
    return number_format($file_size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

/* chuyển date từ 'd/m/Y' sang 'Y-m-d H:i:s' */
function date_convert($date, $time = '00:00:00') {
    $date = str_replace('/', '-', $date);
    return date('Y-m-d H:i:s', strtotime($date .' '. $time));
}

function unnumber_format($number) {
    $number = str_replace(".", "", $number);
    return (double) $number;
}

function download_template($file) {
    $file_path = data_file('import_template/'. $file);
    if ($file_path) {
        return route('download_file', ['path' => Crypt::encryptString('import_template/'. $file)]);
    }

    return '';
}

function permission($code) {
    if (\App\Permission::isAdmin()) {
        return true;
    }

    $permission = \App\Permission::where('code', $code)->first();
    if (empty($permission)) {
        return false;
    }

    if ($permission->unit_permission == 0) {
        return \App\Permission::hasPermission($code);
    }

    return \App\Permission::hasPermissionUnit($code);
}

function link_download($data_path) {
    if (empty($data_path)) {
        return false;
    }

    $storage = \Storage::disk('local');
    if ($storage->exists($data_path)) {
        return route('download_file', ['path' => Crypt::encryptString($data_path)]);
    }

    //$file_name = basename($data_path);
    //$working_dir = urlencode(str_replace('/filemanager', '', str_replace('/'. $file_name, '', $data_path)));
    //return url('/') . '/filemanager/download?working_dir=' . $working_dir . '&type=files&file='. urlencode($file_name);
    return false;
}

function if_empty($var, $default) {
    if (!isset($var)) {
        return $default;
    }
    return empty($var) ? $default : $var;
}

function path_upload($file_path)
{
    $path = explode('uploads/', $file_path);
    if (isset($path[1])) {
        return $path[1];
    }

    $path = explode('filemanager/', $file_path);
    if (isset($path[1])) {
        return $path[1];
    }

    return $file_path;
}

function cal_date($date1, $date2) {
    $diff = abs(strtotime($date2) - strtotime($date1));
    $years = floor($diff / (365*60*60*24));
    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

    $total_year = $years + $months/12 + $days/365;

    return number_format($total_year, 2);
}

function cal_date_by_month($date1, $date2){
    $diff = abs(strtotime($date2) - strtotime($date1));
    $years = floor($diff / (365*60*60*24));
    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

    $total_month = $years*12 + $months;

    return number_format($total_month, 2);
}

function calculate_time_span($date1, $date2){
    $diff = abs($date1 - $date2);

    $years = floor($diff / (365*60*60*24));
    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24) / (60*60*24));
    $hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24) / (60*60));
    $mins = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60) / 60);
    $secs = floor($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $mins*60);

    $time = $mins." phút " .$secs." giây";

    return $time;
}

function is_json($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}

function get_link_to_moodle($uri, $role = 0, $moodlecourseid = 0, $params = []) {
    $url = config('app.moodle_url') . $uri;
    return \Modules\Online\Entities\MoodleCourse::getLinkToMoodle($url, $role, $moodlecourseid, $params);
}

function check_format_date($date){
    if (preg_match("/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/",$date)) {
        return true;
    } else {
        return false;
    }
}

function scoreScorm($user_id, $course_code)
{
    $result = \DB::query()
        ->from('grade_grades as gr')
        ->leftJoin('grade_items as gi', 'gi.id', '=', 'gr.itemid')
        ->leftJoin('course as c', 'c.id', '=', 'gi.courseid')
        ->where('gi.itemtype', '=', 'course')
        ->where('c.idnumber', '=', $course_code)
        ->where('gr.userid', '=', $user_id);

    return $result->first();
}

function data_locale($name, $name_en) {
    $locale = \App::getLocale();
    if ($locale == 'en') {
        if (empty($name_en)) {
            return $name;
        }
        return $name_en;
    }

    return $name;
}

function url_query($to, array $params = [], array $additional = []) {
    return Str::finish(url($to, $additional), '?') . Arr::query($params);
}

function is_youtube_url(string $url) {
    if (strpos($url, 'youtube.com') !== false) {
        return true;
    }

    return false;
}

function get_youtube_id(string $url) {
    $parts = parse_url($url);
    if(isset($parts['query'])){
        parse_str($parts['query'], $qs);
        if(isset($qs['v'])){
            return $qs['v'];
        }else if(isset($qs['vi'])){
            return $qs['vi'];
        }
    }
    if(isset($parts['path'])){
        $path = explode('/', trim($parts['path'], '/'));
        return $path[count($path)-1];
    }
    return false;
}

function shuffle_refer($length=7){
    $chars = '0123456789';
    $arr = str_split($chars, 1);
    shuffle($arr);
    return 'M'.substr(implode('', $arr), 0, $length);
}

function encrypt_array($data = []) {
    $crypt = Crypt::encryptString(json_encode($data));
    $crypt = base64_encode($crypt);
    return urlencode($crypt);
}

function decrypt_array($string) {
    $crypt = urldecode($string);
    $crypt = base64_decode($crypt);
    $crypt = Crypt::decryptString($crypt);
    $crypt = json_decode($crypt, true);
    return $crypt;
}

function userCan($permission = ''){
    if(is_array($permission)) {
        foreach ($permission as $perm) {
            if (!auth()->user()->can($perm)) {
                return false;
            }
        }
        return true;
    }
    return auth()->user()->can($permission);
}

function getPathVideo($path){
    if (strpos($path, 'video')){
        $path = str_replace('/video/', '', $path);
        $path = explode('|', $path);

        $path_name = isset($path[1]) ? $path[1] : '';

        $path = decrypt_array($path[0]);
        return $path['file_path'] . ($path_name ? '|' . $path_name : '') ;
    }
    return $path;
}

function status_register_class($status) {
    switch ($status) {
        case 1: return 'success';
        case 2: return 'danger';
        case 3: return 'danger';
        case 4: return 'success';
        case 5: return 'warning';
        case 6: return 'danger';
        case 7: return 'info';
    }

    return '';
}

function status_register_text($status) {
    switch ($status) {
        case 1: return trans('app.register');
        case 2: return trans('app.expired_registration');
        case 3: return trans('app.ended');
        case 4: return trans('app.come_in_class');
        case 5: return trans('app.unapproved');
        case 6: return trans('app.deny');
        case 7: return trans('app.unopened');
    }

    return '';
}

function get_config($name, $default = null) {
    return \App\Config::getConfig($name, $default);
}

function set_config($name, $value) {
    return \App\Config::setConfig($name, $value);
}

function get_uri($url) {
    return str_replace(request()->getSchemeAndHttpHost() . '/', '', $url);
}

function getUserType() {
    if (\Auth::check()) {
        return 1;
    }

    if (\Auth::guard('secondary')->check()) {
        return 2;
    }

    return null;
}

function getUserId() {
    if (\Auth::check()) {
        return \Auth::id();
    }

    if (\Auth::guard('secondary')->check()) {
        return \Auth::guard('secondary')->id();
    }

    return null;
}
function numberFormat($number,$decimail=0,$culture='general'){
    if ($culture=='vn')
        return number_format($number,$decimail,',','.');
    return number_format($number,$decimail);
}
function dateDiffSql($fdate,$tdate,$interval='day'){
    if (strtotime($tdate) !== false)
        $tdate ="'".$tdate."'";
    if (strtotime($fdate) !== false)
        $fdate ="'".$fdate."'";
    $dbDriver = strtolower(DB::connection()->getDriverName());
    if ($dbDriver=='mysql'){
        return "TIMESTAMPDIFF($interval,$fdate,$tdate)";
    }elseif ($dbDriver=='sqlsrv')
        return "DATEDIFF($interval,$fdate,$tdate)";
    return '';
}
function unix_timestamp_sql($date=null){
    $dbDriver = strtolower(DB::connection()->getDriverName());
    if ($dbDriver=='mysql'){
        if ($date)
            return "UNIX_TIMESTAMP($date)";
        else
            return "UNIX_TIMESTAMP()";
    }elseif ($dbDriver=='sqlsrv')
        if ($date)
            return "DATEDIFF_BIG(SECOND, '1970-01-01 00:00:00', $date)";
        else
            return "DATEDIFF_BIG(SECOND, '1970-01-01 00:00:00', GETDATE())";
    return '';
}
function unix_todatetime_sql($timestamp){
    $dbDriver = strtolower(DB::connection()->getDriverName());
    if ($dbDriver=='mysql'){
            return "FROM_UNIXTIME($timestamp)";
    }elseif ($dbDriver=='sqlsrv')
            return "DATEDIFF_BIG(SECOND, $timestamp,'1970-01-01 00:00:00')";
    return '';
}
function current_datetime_sql(){
    $dbDriver = strtolower(DB::connection()->getDriverName());
    if ($dbDriver=='mysql'){
        return "now()";
    }elseif ($dbDriver=='sqlsrv')
        return "GETDATE()";
    return '';
}
function dateAddSql($date, $num, $interval='month'){
    $dbDriver = strtolower(DB::connection()->getDriverName());
    if ($dbDriver=='mysql'){
        if (strtotime($date) !== false){
            $date ="'".get_date($date, 'Y-m-d')."'";
        }

        $interval = \Illuminate\Support\Str::upper($interval);
        return "DATE_ADD($date, INTERVAL $num $interval)";

    }elseif ($dbDriver=='sqlsrv'){
        if (strtotime($date) !== false){
            $date ="'".get_date($date, 'Y/m/d')."'";
        }

        return "DATEADD($interval, $num, $date)";
    }

    return '';
}
function url_mobile(){
    $domain = parse_url(request()->url(), PHP_URL_HOST);
    if ($domain == parse_url(config('app.mobile_url'),PHP_URL_HOST)){
        return true;
    }

    return false;
}

function userThird(){
    if (\Auth::check()) {
        $user = \App\Profile::whereUserId(\Auth::id())->first();
        if ($user && $user->type_user == 2){
            return true;
        }
    }

    return false;
}

function getMailSignature($user_id = null, $user_type = 1){
    if ($user_type == 1){
        $user_id = empty($user_id) ? Auth::id() : $user_id;
        $company = Profile::getCompany($user_id);
    }else{
        $user_id = empty($user_id) ? \Auth::guard('secondary')->id() : $user_id;
        $company = QuizUserSecondary::getCompany($user_id);
    }
    $signature = MailSignature::where('unit_id', $company)->first();

    return ($signature ? $signature->content : '');
}
function isMobile() {
    return preg_match("/(android|webos|avantgo|iphone|ipad|ipod|blackberry|iemobile|bolt|boost|cricket|docomo|fone|hiptop|mini|opera mini|kitkat|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);;
}

function isFilePdf($attachment) {
    if (empty($attachment)) {
        return false;
    }

    $extention = pathinfo($attachment, PATHINFO_EXTENSION);
    if ($extention == 'pdf' || $extention == 'PDF') {
        return true;
    }

    return false;
}

function get_menu_child($name) {
    $items_manager = [
        [
            'name' => trans('backend.category'),
            'url' => route('backend.category'),
            'name_url' => 'category',
            'permission' => User::canPermissionCategoryUnit() || User::canPermissionCategoryCourse() || User::canPermissionCategoryQuiz() || User::canPermissionCategoryCost()
            || User::canPermissionCategoryTeacher() || User::canPermissionCategoryProvince(),
        ],
        [
            'name' => trans('backend.user'),
            'url' => route('module.backend.user'),
            'name_url' => 'user',
            'permission' => userCan('user') || Permission::isUnitManager(),
        ],
        [
            'name' => trans('career.career_roadmap'),
            'url' => route('module.career_roadmap'),
            'name_url' => 'career-roadmap',
            'permission' => userCan('career-roadmap'),
        ],
        [
            'name' => trans('backend.survey'),
            'url' => route('module.survey.index'),
            'name_url' => 'survey',
            'permission' => userCan('survey'),
        ],
        [
            'name' => trans('lacore.situations_proccessing'),
            'url' => route('module.topic_situations'),
            'name_url' => 'topic-situations',
            'permission' => userCan('topic'),
        ],
        [
            'name' => trans('backend.forum'),
            'url' => route('module.forum.category'),
            'name_url' => 'forums',
            'permission' => userCan('forum')
        ],
        [
            'name' => trans('backend.suggestion'),
            'url' => route('module.suggest'),
            'name_url' => 'suggest',
            'permission' => userCan('suggest')
        ],
        [
            'name' => trans('lacore.note'),
            'url' => route('backend.note'),
            'name_url' => 'note',
            'permission' => userCan('note'),
        ],
        [
            'name' => trans('backend.history'),
            'url' => route('module.modelhistory.index'),
            'name_url' => 'history',
            'permission' => userCan('model-history') || userCan('log-view-course'),
        ],
        [
            'name' => trans('backend.faq'),
            'url' => route('module.faq'),
            'name_url' => 'faq',
            'permission' => userCan('FAQ'),
        ],
        [
            'name' => trans('backend.guide'),
            'url' => route('backend.guide'),
            'name_url' => 'guide',
            'permission' => userCan('guide'),
        ],
        [
            'name' => trans('backend.plan_suggest'),
            'url' => route('module.plan_suggest'),
            'name_url' => 'plan-suggest',
            'permission' => userCan('plan-suggest'),
        ],
        [
            'name' => 'API',
            'url' => route('backend.manual-api'),
            'name_url' => 'manual-api',
            'permission' => userCan('api-manual'),
        ],
        [
            'name' => trans('backend.schedule_task'),
            'url' => route('module.cron'),
            'name_url' => 'cron',
            'permission' => Permission::isSuperAdmin(),
        ],
        [
            'name' => trans('lamanager.table_manager'),
            'url' => route('module.tablemanager.index'),
            'name_url' => 'table-manager',
            'permission' => Permission::isSuperAdmin(),
        ],
    ];

    $items_learning = [
        [
            'name' => trans('backend.training_organizations'),
            'url' => route('module.online.management'),
            'name_url' => 'training_organizations',
            'permission' => User::canPermissionTrainingOrganization(),
        ],
        [
            'name' => trans('lacourse.learning_manager'),
            'url' => route('module.trainingroadmap'),
            'name_url' => 'learning_manager',
            'permission' => User::canPermissionTrainingManager(),
        ],
        [
            'name' => trans('backend.subject_registered'),
            'url' => route('subjectregister.index'),
            'name_url' => 'subjectregister',
            'permission' => userCan('subjectregister'),
        ],
        [
            'name' => trans('backend.indemnify'),
            'url' => route('module.indemnify'),
            'name_url' => 'indemnify',
            'permission' => userCan('indemnify'),
        ],
        [
            'name' => trans('backend.certificate'),
            'url' => route('module.certificate'),
            'name_url' => 'certificate',
            'permission' => userCan('certificate-template'),
        ],
        [
            'name' => trans('lacourse.evaluate_training_effectiveness'),
            'url' => route('module.rating.template'),
            'name_url' => 'evaluate_training_effectiveness',
            'permission' => userCan('rating-template') || userCan('rating-levels'),
        ],
        [
            'name' => trans('backend.new_report'),
            'url' => route('module.report_new'),
            'name_url' => 'report-new',
            'permission' => User::canPermissionReport(),
        ],
    ];

    $item_libraries = [
        [
            'name' => trans('backend.category'),
            'url' => route('module.libraries.category'),
            'name_url' => 'category-libraries',
            'permission' => userCan('libraries-category'),
        ],
        [
            'name' => trans('backend.book_register'),
            'url' => route('module.libraries.book.register'),
            'name_url' => 'register',
            'permission' => userCan('libraries-book-register'),
        ],
        [
            'name' => trans('backend.book'),
            'url' => route('module.libraries.book'),
            'name_url' => 'book',
            'permission' => userCan('libraries-book'),
        ],
        [
            'name' => trans('backend.ebook'),
            'url' => route('module.libraries.ebook'),
            'permission' => userCan('libraries-ebook'),
            'name_url' => 'ebook',
        ],
        [
            'name' => trans('backend.document'),
            'url' => route('module.libraries.document'),
            'permission' => userCan('libraries-document'),
            'name_url' => 'document',
        ],
        [
            'name' => trans('lageneral.audio'),
            'url' => route('module.libraries.audiobook'),
            'permission' => userCan('libraries-ebook'),
            'name_url' => 'audiobook',
        ],
        [
            'name' => 'Video',
            'url' => route('module.libraries.video'),
            'permission' => userCan('libraries-video'),
            'name_url' => 'video',
        ],
    ];

    $items_news = [
        [
            'name' => trans('backend.category'),
            'url' => route('module.news.category'),
            'permission' => userCan('news-category'),
            'name_url' => 'category-news',
        ],
        [
            'name' => trans('backend.news_list'),
            'url' => route('module.news.manager'),
            'permission' => userCan('news-list'),
            'name_url' => 'news',
        ],
        [
            'name' => trans('backend.cate_news_general'),
            'url' => route('module.news_outside.category'),
            'permission' => Permission::isAdmin() || userCan('news-outside-category'),
            'name_url' => 'category-news-outside',
        ],
        [
            'name' => trans('backend.news_list_outside'),
            'url' => route('module.news_outside.manager'),
            'permission' => Permission::isAdmin() || userCan('news-outside-list'),
            'name_url' => 'news-outside',
        ],
        [
            'name' => trans('lamanager.news_adv_banner'),
            'url' => route('backend.advertising_photo',['type' => 1]),
            'permission' => userCan('advertising-photo'),
            'name_url' => 'advertising-photo',
        ],
    ];

    $item_promotion = [
        [
            'name' => trans('backend.promotion_category_group'),
            'url' => route('module.promotion.group'),
            'name_url' => 'promotion-group',
            'permission' => userCan('promotion-group'),
        ],
        [
            'name' => trans('backend.promotions'),
            'url' => route('module.promotion'),
            'name_url' => 'promotion',
            'permission' => userCan('promotion'),
        ],
        [
            'name' => trans('backend.purchase_history'),
            'url' => route('module.promotion.orders.buy'),
            'name_url' => 'promotion-orders',
            'permission' => userCan('promotion-purchase-history'),
        ],
        [
            'name' => trans('backend.donate_points'),
            'url' => route('backend.donate_points'),
            'name_url' => 'donate-points',
            'permission' => userCan('donate-point'),
        ],
        [
            'name' => trans('backend.user_level_setting'),
            'url' => route('module.promotion.level'),
            'name_url' => 'promotion-level',
            'permission' => userCan('promotion-level'),
        ],
        [
            'name' => trans('lamanager.emulation_program'),
            'url' => route('backend.emulation_program'),
            'name_url' => 'emulation-program',
            'permission' => userCan('emulation-program'),
        ],
    ];

    $item_daily_training = [
        [
            'name' => trans('backend.video_category'),
            'url' => route('module.daily_training'),
            'name_url' => 'daily-training',
            'permission' => userCan('daily-training'),
        ],
        [
            'name' => trans('backend.setting_views'),
            'url' => route('module.daily_training.score_views'),
            'name_url' => 'score-views',
            'permission' => userCan('score-view'),
        ],
        [
            'name' => trans('backend.setting_like'),
            'url' => route('module.daily_training.score_like'),
            'name_url' => 'score-like',
            'permission' => userCan('score-like'),
        ],
        [
            'name' => trans('backend.setting_comment'),
            'url' => route('module.daily_training.score_comment'),
            'name_url' => 'score-comment',
            'permission' => userCan('score-comment'),
        ],
    ];

    $item_permission = [
        [
            'name' => trans('backend.permission_group'),
            'url' => route('module.permission.type'),
            'name_url' => 'permission-type',
            'permission' => userCan('permission-group'),
        ],
        [
            'name' => trans('backend.role'),
            'url' => route('backend.roles'),
            'name_url' => 'role',
            'permission' => userCan('role'),
        ],
        [
            'name' => trans('backend.permission_approved'),
            'url' => route('backend.approved.process.index'),
            'name_url' => 'approved-process',
            'permission' => userCan('approved-process'),
        ],
        [
            'name' => trans('backend.unit_manager_setup'),
            'url' => route('backend.permission.unitmanager'),
            'permission' => userCan('unit-manager-setting'),
            'name_url' => 'unit-manager',
        ],
    ];

    $item_units_func = [
        [
            'name' => trans('app.approve_register'),
            'url' => route('module.training_unit.approve_course'),
            'name_url' => 'approve-course',
            'permission' => Permission::isUnitManager() || Permission::isAdmin() || userCan('online-course-register') || userCan('offline-course-register'),
        ],
        [
            'name' =>trans('lageneral.approve_student_cost'),
            'url' => route('module.training_unit.approve_student_cost'),
            'name_url' => 'approve-student-cost',
            'permission' => Permission::isUnitManager() || Permission::isAdmin(),
        ],
        [
            'name' => trans('backend.plan_app'),
            'url' => route('module.plan_app.course'),
            'name_url' => 'plan-app',
            'permission' => Permission::isUnitManager() || userCan('plan-app'),
        ],
        [
            'name' => trans('lageneral.training_seft_plan'),
            'url' => route('module.course_educate_plan.management'),
            'name_url' => 'course-educate-plan',
            'permission' => Permission::isUnitManager() || Permission::isAdmin(),
        ],
        [
            'name' => trans('lageneral.quiz_plan_suggest'),
            'url' => route('module.quiz_educate_plan_suggest'),
            'name_url' => 'quiz-educate-plan',
            'permission' => Permission::isUnitManager() || Permission::isAdmin(),
        ],
        [
            'name' => trans('lageneral.authorized_unit_manager'),
            'url' => route('module.authorized_unit'),
            'name_url' => 'authorized-unit',
            'permission' => Permission::isUnitManager(Auth::id(), 1) || Permission::isAdmin(),
        ],
    ];

    $item_quiz = [
        [
            'name' => trans('backend.questionlib'),
            'url' => route('module.quiz.questionlib'),
            'name_url' => 'question-lib',
            'permission' => userCan('quiz-category-question'),
        ],
        [
            'name' => trans('lacourse.quiz_structure'),
            'url' => route('module.quiz_template.manager'),
            'name_url' => 'quiz-template',
            'permission' => userCan('quiz-template')
        ],
        [
            'name' => trans('backend.quiz_list'),
            'url' => route('module.quiz.manager'),
            'name_url' => 'quiz',
            'permission' => userCan('quiz')
        ],
        [
            'name' => trans('lacourse.data_old_quiz'),
            'url' => route('module.quiz.data_old_quiz'),
            'name_url' => 'data-old',
            'permission' => userCan('quiz')
        ],
        [
            'name' => trans('backend.grading'),
            'url' => route('module.quiz.grading'),
            'name_url' => 'grading',
            'permission' => \Auth::user()->isTeacher() || userCan('quiz-grading')
        ],
        [
            'name' => trans('backend.statistic'),
            'url' => route('module.quiz.dashboard'),
            'name_url' => 'dashboard',
            'permission' => userCan('quiz-dashboard')
        ],
        [
            'name' => trans('lacourse.setting_alert'),
            'url' => route('module.quiz.setting_alert'),
            'name_url' => 'setting-alert',
            'permission' => userCan('quiz-setting-alert')
        ],
        [
            'name' =>trans('lacourse.information_edit'),
            'url' => route('module.quiz.user_second_note'),
            'name_url' => 'user-second-note',
            'permission' => userCan('quiz')
        ],
        [
            'name' => trans('backend.user_secondary'),
            'url' => route('module.quiz.user_secondary'),
            'name_url' => 'user-secondary',
            'permission' => userCan('quiz-user-secondary')
        ],
        [
            'name' => trans('backend.history'),
            'url' => route('module.quiz.history_user'),
            'name_url' => 'history',
            'permission' => userCan('quiz-history')
        ],
    ];
    
    $menu_manager = ['category','topic-situations','user','forums','suggest','note','survey','career-roadmap','plan-suggest','model-history','user-contact','manual-api','faq','guide','table-manager','cron','user-take-leave','user-contact','login-history','log-view-course'];

    $menu_learning_opening = ['online','offline','training-plan','course-plan','courseold','trainingroadmap','training-by-title','training-by-title-result','mergesubject','splitsubject','subjectcomplete','movetrainingprocess','subjectregister','indemnify','certificate','evaluationform','rating-organization','report-new'];

    $menu_libraires = ['category-libraries','book','ebook','document','audiobook','video'];

    $menu_news = ['category-news','news','advertising-photo','category-news-outside','news-outside'];

    $menu_promotion = ['promotion-group','promotion','promotion-orders','donate-points','promotion-level','emulation-program'];

    $menu_training_video = ['daily-training','score-views','score-like','score-comment'];

    $menu_permission = ['permission-type','role','approved-process','unit-manager'];

    $menu_unit = ['approve-course','approve-student-cost','plan-app','course-educate-plan','quiz-educate-plan','authorized-unit'];

    $menu_quiz = ['question-lib','quiz-template','quiz','data-old','grading','dashboard','setting-alert','user-second-note','user-secondary','history'];
    // dd(session()->get('menu_manager'));
    if (in_array($name, $menu_manager)) {
        return [$items_manager, 'menu_manager'];
    } else if (in_array($name, $menu_learning_opening)) {
        return [$items_learning, 'menu_learning_opening'];
    } else if (in_array($name, $menu_libraires)) {
        return [$item_libraries, 'menu_libraires'];
    } else if (in_array($name, $menu_news)) {
        return [$items_news, 'menu_news'];
    } else if (in_array($name, $menu_promotion)) {
        return [$item_promotion, 'menu_promotion'];
    } else if (in_array($name, $menu_training_video)) {
        return [$item_daily_training, 'menu_training_video'];
    } else if (in_array($name, $menu_permission)) {
        return [$item_permission, 'menu_permission'];
    } else if (in_array($name, $menu_unit)) {
        return [$item_units_func, 'menu_unit'];
    } else if (in_array($name, $menu_quiz)) {
        return [$item_quiz, 'menu_quiz'];
    }
}
