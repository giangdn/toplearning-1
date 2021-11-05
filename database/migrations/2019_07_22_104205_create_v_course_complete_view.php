<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVCourseCompleteView extends Migration
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
         CREATE VIEW v_course_complete
         AS
         SELECT *, 1 AS course_type FROM mdl_el_online_course_complete
         UNION ALL
         SELECT *, 2 AS course_type FROM mdl_el_offline_course_complete
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS v_course_complete');
    }
}
