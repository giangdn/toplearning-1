<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineCourseMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_offline_course_meta', function (Blueprint $table) {
            $table->bigInteger('course_id');
            $table->string('key', 150);
            $table->text('value')->nullable();

            $table->primary(['course_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_offline_course_meta');
    }
}
