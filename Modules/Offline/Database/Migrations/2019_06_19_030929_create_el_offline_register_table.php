<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineRegisterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_offline_register', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('course_id');
            $table->integer('status')->default(2);
            $table->string('note')->nullable();
            $table->bigInteger('created_by')->index()->default(2);
            $table->bigInteger('updated_by')->index()->default(2);
            $table->integer('unit_by')->nullable()->index();
            $table->tinyInteger('cron_complete')->index()->nullable()->comment('1 đã chạy cron complete, 0 chưa chạy, null không chạy');
            $table->string('approved_step')->nullable();
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
        Schema::dropIfExists('el_offline_register');
    }
}
