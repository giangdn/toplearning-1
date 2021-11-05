<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlinePermission
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlinePermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlinePermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlinePermission query()
 * @mixin \Eloquent
 */
class OnlinePermission extends Model
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
}
