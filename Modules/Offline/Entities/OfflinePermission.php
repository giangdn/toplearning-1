<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflinePermission
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflinePermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflinePermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflinePermission query()
 * @mixin \Eloquent
 */
class OfflinePermission extends Model
{
    public static function createCourse($course = null) {
        return true;
    }

    public static function editCourse($course = null) {
        return true;
    }

    public static function saveCourse($course) {
        if (empty($course->id)) {
            return self::createCourse();
        }

        if ($course->id) {
            return self::editCourse($course);
        }

        return false;
    }

    public static function viewRegister($course) {
        return true;
    }

    public static function createRegister($course) {
        return true;
    }

    public static function approveRegisterCourse($course) {
        return true;
    }

    public static function viewTeacher($course) {
        return true;
    }

    public static function viewAttendance($course) {
        return true;
    }

    public static function viewResult($course) {
        return true;
    }

}
