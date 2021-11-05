<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVCourseRegisterView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        return true;
        DB::statement('
        CREATE VIEW v_course_register
        AS
        SELECT a.id, a.user_id,a.course_id,a.status,a.note, 1 AS course_type , b.score, NULL AS finish_date
		FROM mdl_el_online_register a
		LEFT JOIN mdl_el_online_result b ON a.id=b.register_id
        UNION ALL
        SELECT a.id, a.user_id,a.course_id,a.status,a.note, 2 AS course_type, b.score, a.updated_at AS finish_date
		FROM mdl_el_offline_register a
		LEFT JOIN mdl_el_offline_result b ON a.id=b.register_id
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS v_course_register");
    }
}
