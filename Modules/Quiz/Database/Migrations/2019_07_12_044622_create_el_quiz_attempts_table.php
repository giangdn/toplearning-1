<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizAttemptsTable extends Migration
{
    public function up()
    {
        Schema::create('el_quiz_attempts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quiz_id')->index();
            $table->bigInteger('part_id')->index();
            $table->bigInteger('user_id')->index();
            $table->tinyInteger('type')->index()->default(1)->comment('1: Người thi trong, 2: Người thi ngoài');
            $table->integer('attempt')->comment('Số lần thử');
            $table->string('state', 20)->index()->comment('Trạng thái');
            $table->bigInteger('timestart')->comment('Thời gian bắt đầu');
            $table->bigInteger('timefinish')->default(0)->comment('Thời gian hoàn thành');
            $table->decimal('sumgrades')->default(0)->comment('Tổng điểm đạt được');
            $table->integer('created_by')->nullable()->default(2)->index();
            $table->integer('updated_by')->nullable()->default(2)->index();
            $table->integer('unit_by')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_quiz_attempts');
    }
}
