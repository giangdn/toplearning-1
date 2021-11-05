<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElPlanAppTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_plan_app_template', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',250)->nullable();
            $table->bigInteger('created_by')->nullable()->default(2)->index();
            $table->bigInteger('updated_by')->nullable()->default(2)->index();
            $table->integer('unit_by')->nullable()->index();
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
//        Schema::table('el_plan_app_template_cate', function (Blueprint $table) {
//            $table->dropForeign(['plan_app_id']);
//        });
        Schema::dropIfExists('el_plan_app_template');
    }
}
