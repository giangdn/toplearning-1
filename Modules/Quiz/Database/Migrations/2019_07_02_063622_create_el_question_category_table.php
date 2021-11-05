<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuestionCategoryTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('el_question_category')) {
            Schema::create('el_question_category', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->bigInteger('parent_id')->index()->nullable();
                $table->tinyInteger('status')->index()->default(1);
                $table->bigInteger('unit_id')->index()->nullable();
                $table->bigInteger('created_by')->nullable()->index();
                $table->bigInteger('updated_by')->nullable()->index();
                $table->integer('unit_by')->nullable()->index();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        /*Schema::dropIfExists('el_question_category');*/
    }
}
