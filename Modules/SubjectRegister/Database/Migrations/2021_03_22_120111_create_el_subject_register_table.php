<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElSubjectRegisterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_subject_register', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('user_type')->default(1);
            $table->integer('subject_id');
            $table->integer('status')->index()->default(1)->comment('1: Đã đăng ký, 2: Hủy đăng ký');
            $table->string('note')->nullable();
            $table->integer('created_by')->index()->nullable();
            $table->integer('updated_by')->index()->nullable();
            $table->integer('unit_by')->index()->nullable();
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
        Schema::dropIfExists('el_subject_register');
    }
}
