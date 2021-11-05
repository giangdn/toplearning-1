<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineCourseActivityScormsTable extends Migration
{
    public function up()
    {
        Schema::create('el_online_course_activity_scorms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id')->index();
            $table->bigInteger('scorm_id')->index();
            $table->string('path', 150)->index();
            $table->integer('max_attempt')->default(0)->comment('0: Không giới hạn');
            $table->integer('what_grade')->default(1)->comment('1: Lần cao nhất, 2: trung bình, 3: lần đầu, 4: lần cuối');
            $table->integer('max_score')->default(100);
            $table->tinyInteger('score_required')->default(0);
            $table->tinyInteger('status_passed')->default(0);
            $table->tinyInteger('status_completed')->default(0);
            $table->tinyInteger('new_attempt_required')->default(0)->comment('1: khi có kết quả, 2: luôn luôn');
            $table->integer('min_score_required')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_online_course_activity_scorms');
    }
}
