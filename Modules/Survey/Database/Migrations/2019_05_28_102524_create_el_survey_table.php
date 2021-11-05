<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_survey', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->bigInteger('template_id');
            $table->tinyInteger('status');
            $table->string('image')->nullable();
            $table->tinyInteger('more_suggestions')->comment('Đề xuất khác');
            $table->text('custom_template')->nullable()->comment('Mẫu tuỳ chỉnh');
            $table->integer('created_by')->nullable()->index();
            $table->integer('updated_by')->nullable()->index();
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
        Schema::dropIfExists('el_survey');
    }
}
