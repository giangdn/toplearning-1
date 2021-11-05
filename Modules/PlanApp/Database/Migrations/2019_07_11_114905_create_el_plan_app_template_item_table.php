<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElPlanAppTemplateItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_plan_app_template_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',250)->comment('Tiêu đề');
            $table->string('data_type',250)->comment('kiểu dữ liệu');
            $table->integer('sort')->nullable()->comment('thứ tự');
            $table->bigInteger('cate_id')->comment('đề mục');
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
        Schema::dropIfExists('el_plan_app_template_item');
    }
}
