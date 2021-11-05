<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVCourseView extends Migration
{
    public function up()
    {
        return true;
        DB::statement("
        CREATE VIEW v_course
        AS
        SELECT
            a.id,
            a.code,
            a.name,
            a.status,
            a.isopen,
            b.id AS training_program_id,
            b.name AS training_program_name,
            c.id AS subject_id,
            c.name AS subject_name,
            a.start_date,
            a.end_date,
            a.register_deadline,
            2 AS course_type,
            a.action_plan,
            d.name AS training_location_name,
            a.training_unit,
            a.cert_code,
            a.course_time,
            a.commit_date,
            a.image,
            a.views
        FROM mdl_el_offline_course a
        LEFT JOIN mdl_el_training_program b ON a.training_program_id=b.id
        LEFT JOIN mdl_el_subject c ON c.id=a.subject_id
        LEFT JOIN mdl_el_training_location d ON d.id=a.training_location_id
        UNION ALL
        SELECT
            a.id,
            a.code,
            a.name,
            a.status,
            a.isopen,
            b.id AS training_program_id,
            b.name AS training_program_name,
            c.id AS subject_id,
            c.name AS subject_name,
            a.start_date,
            a.end_date,
            a.register_deadline,
            1 AS course_type,
            a.action_plan,
            NULL AS training_location_name,
            NULL AS training_unit,
            a.cert_code,
            a.course_time,
            null as commit_date,
            a.image,
            a.views
        FROM mdl_el_online_course a
        LEFT JOIN mdl_el_training_program b ON a.training_program_id=b.id
        LEFT JOIN mdl_el_subject c ON c.id=a.subject_id");
    }

    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS v_course");
    }
}
