<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizResultTable extends Migration
{
    public function up()
    {
        Schema::create('el_quiz_result', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quiz_id')->index();
            $table->bigInteger('user_id')->index();
            $table->tinyInteger('type')->index()->comment('1: người thi trong, 2: người thi ngoài');
            $table->double('grade')->nullable()->comment('Điểm');
            $table->double('reexamine')->nullable()->comment('Điểm phúc khảo');
            $table->string('attach_file')->nullable()->comment('File đính kèm thi giấy');
            $table->bigInteger('timecompleted')->default(0)->comment('Thời gian hoàn thành');
            $table->integer('result')->index()->default(-1)->comment('Kết quả đậu rớt 1: điểm thi > điểm chuẩn, 0 ngược lại');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_quiz_result');
    }
}
