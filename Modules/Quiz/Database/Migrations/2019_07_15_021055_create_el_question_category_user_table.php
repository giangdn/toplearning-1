<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuestionCategoryUserTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('el_question_category_user')) {
            Schema::create('el_question_category_user', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('category_id')->index();
                $table->bigInteger('unit_id')->index();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        /*Schema::dropIfExists('el_question_category_user');*/
    }
}
