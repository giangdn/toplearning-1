<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableElOfflineCourseEdit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('el_offline_course', function (Blueprint $table) {
            $table->integer('max_grades')->default(0)->nullable();
			$table->integer('min_grades')->default(0)->nullable();
			$table->integer('course_employee')->default(0)->nullable();
			$table->integer('course_action')->default(0)->nullable();
			$table->integer('title_join_id')->default(0)->nullable();
			$table->integer('title_recommend_id')->default(0)->nullable();
			$table->integer('training_object_id')->default(0)->nullable();
			$table->integer('teacher_type_id')->default(0)->nullable();
			$table->integer('training_type_id')->default(0)->nullable();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::table('el_offline_course', function (Blueprint $table)
        {
            $table->dropColumn('max_grades');
            $table->dropColumn('min_grades');
            $table->dropColumn('course_employee');
            $table->dropColumn('course_action');
            $table->dropColumn('title_join_id');
            $table->dropColumn('title_recommend_id');
            $table->dropColumn('training_object_id');
            $table->dropColumn('teacher_type_id');
            $table->dropColumn('training_type_id');
        });*/

    }
}
