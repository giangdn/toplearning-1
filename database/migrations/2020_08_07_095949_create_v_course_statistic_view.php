<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVCourseStatisticView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        return true;
        DB::statement("
        CREATE VIEW v_course_statistic_view
        AS
        select
        ( select count(1) as course_passed 
            from v_course 
            where status=1 
            and start_date<=DATE_FORMAT(now(),'%Y-%c-%d')
        ) as course_passed,
        ( select count(1) as course_not_passed 
            from v_course 
            where status=1 
            and start_date>DATE_FORMAT(now(),'%Y-%c-%d')
        ) as course_not_passed,
        ( select count(1) as course_pending 
            from v_course 
            where status=2
        ) as course_pending,
        ( select count(1) as course_deny 
            from v_course 
            where status=0
        ) as course_deny,
        ( select count(1) as course_total 
            from v_course
        ) as course_total
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS v_course_statistic_view");
    }
}
