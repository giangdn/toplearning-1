<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCronTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_cron', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->comment('Mã');
            $table->string('command')->comment('Tác vụ');
            $table->string('description', 256)->nullable()->comment('Miêu tả');
            $table->string('minute')->nullable()->comment('phút');
            $table->string('hour')->nullable()->comment('giờ');
            $table->string('day')->nullable()->comment('ngày');
            $table->string('month')->nullable()->comment('tháng');
            $table->string('day_of_week')->nullable()->comment('ngày của tuần');
            $table->string('expression')->nullable()->comment('biểu thức');
            $table->dateTime('last_run')->nullable();
            $table->integer('enabled')->default(1)->nullable()->comment('1 enabled 0 disabled');
            $table->bigInteger('created_by')->index()->nullable();
            $table->bigInteger('updated_by')->index()->nullable();
            $table->integer('unit_by')->nullable();
            $table->time('start_time')->nullable()->comment('thời gian bắt đầu');
            $table->time('end_time')->nullable()->comment('Thời gian kết thúc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_cron');
    }
}
