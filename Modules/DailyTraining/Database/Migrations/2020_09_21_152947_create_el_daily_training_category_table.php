<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElDailyTrainingCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_daily_training_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('status_video')->default(0)->comment('0: video đăng là duyệt luôn. 1: video đăng phải chờ duyệt');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('unit_by')->nullable();
            $table->timestamps();
        });

        DB::table('el_daily_training_category')->insert([
            'name' => 'Mặc định',
            'status_video' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_daily_training_category');
    }
}
