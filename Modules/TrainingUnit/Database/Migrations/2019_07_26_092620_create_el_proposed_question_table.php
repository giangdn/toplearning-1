<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElProposedQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_proposed_question', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name');
            $table->string('type')->comment('essay: Tự luận/ multiple-choise: Trắc nghiệm');
            $table->bigInteger('category_id')->nullable();
            $table->integer('multiple')->default(0)->comment('chọn nhiều');
            $table->integer('status')->default(0);
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
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
        Schema::dropIfExists('el_proposed_question');
    }
}
