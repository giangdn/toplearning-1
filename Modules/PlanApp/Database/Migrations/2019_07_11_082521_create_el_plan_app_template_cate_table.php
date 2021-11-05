<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElPlanAppTemplateCateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_plan_app_template_cate', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('plan_app_id');
            $table->string('name',250)->comment('Đề mục');
            $table->integer('sort')->comment('thứ tự');
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
        Schema::dropIfExists('el_plan_app_template_cate');
    }
}
