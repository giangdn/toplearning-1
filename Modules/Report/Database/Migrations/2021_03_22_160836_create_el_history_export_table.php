<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElHistoryExportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_history_export', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('report_name', 250);
            $table->string('file_name', 250)->nullable();
            $table->string('class_name')->nullable();
            $table->text('request')->nullable();
            $table->text('error')->nullable();
            $table->integer('status')->default(2)->comment('0: error, 1: ok, 2: pedding, 3: exporting');
            $table->bigInteger('user_id')->index();
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
        Schema::dropIfExists('el_history_export');
    }
}
