<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizTypeTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('el_quiz_type')) {
            Schema::create('el_quiz_type', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name', 250);
                $table->integer('created_by')->nullable()->index();
                $table->integer('updated_by')->nullable()->index();
                $table->integer('unit_by')->nullable()->index();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        /*Schema::dropIfExists('el_quiz_type');*/
    }
}
