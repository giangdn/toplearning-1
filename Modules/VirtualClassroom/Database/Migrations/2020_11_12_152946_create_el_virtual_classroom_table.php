<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElVirtualClassroomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_virtual_classroom', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 150)->unique();
            $table->string('name');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->longText('content')->nullable();
            $table->integer('status')->default(2);
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->bigInteger('course_id')->default(0);
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
        Schema::dropIfExists('el_virtual_classroom');
    }
}
