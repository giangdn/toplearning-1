<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOnlineCourseActivityScormsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('el_online_course_activity_scorms', 'new_attempt_required')) {
            Schema::table('el_online_course_activity_scorms', function (Blueprint $table) {
                $table->tinyInteger('new_attempt_required')->default(0)->comment('1: khi có kết quả, 2: luôn luôn');
            });
        }
    }
    
    public function down()
    {
    
    }
}
