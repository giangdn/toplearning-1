<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableElOnlineCourseEdit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('el_online_course', function (Blueprint $table) {
            $table->integer('max_grades')->default(0)->nullable();
			$table->integer('min_grades')->default(0)->nullable();
			$table->integer('title_join_id')->default(0)->nullable();
			$table->integer('title_recommend_id')->default(0)->nullable();
			$table->integer('training_object_id')->default(0)->nullable();
			$table->integer('is_limit_time')->default(0)->nullable();
			$table->string('start_timeday')->default('')->nullable();
			$table->string('end_timeday')->default('')->nullable();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::table('el_online_course', function (Blueprint $table) {
            $table->dropColumn('max_grades');
            $table->dropColumn('min_grades');
            $table->dropColumn('title_join_id');
            $table->dropColumn('title_recommend_id');
            $table->dropColumn('training_object_id');
            $table->dropColumn('is_limit_time');
            $table->dropColumn('start_timeday');
            $table->dropColumn('end_timeday');
        });*/
    }
}
