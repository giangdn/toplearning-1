<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElMoveTrainingProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_move_training_process', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('employee_old');
            $table->integer('employee_new');
            $table->text('move_process_id')->nullable();
            $table->integer('status')->nullable()->comment('null: Chưa duyệt, 1: Đã duyệt, 0: từ chối');
            $table->dateTime('approved_date')->nullable();
            $table->integer('approved_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('unit_by')->nullable();
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
        Schema::dropIfExists('el_move_training_process');
    }
}
