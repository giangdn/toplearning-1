<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElSubjectTable extends Migration
{
    public function up()
    {
        //if (!Schema::hasTable('el_subject')) {
            Schema::create('el_subject', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('code', 150)->unique();
                $table->string('name');
                $table->bigInteger('training_program_id')->unsigned();
                $table->bigInteger('level_subject_id')->unsigned()->nullable();
                $table->dateTime('created_date')->nullable()->comment('ngày khởi tạo');
                $table->bigInteger('created_by')->nullable();
                $table->bigInteger('unit_id')->nullable();
                $table->text('description')->nullable();
                $table->longText('content')->nullable();
                $table->integer('status')->default(1);
                $table->string('condition')->nullable();
                $table->integer('updated_by')->nullable()->index();
                $table->integer('unit_by')->nullable()->index();
                $table->timestamps();
            });
        //}
    }

    public function down()
    {
        Schema::dropIfExists('el_subject');
    }
}
