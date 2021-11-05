<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineReferenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_offline_reference', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('register_id');
            $table->bigInteger('schedule_id');
            $table->string('reference')->nullable()->comment('Đơn xin phép');
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
        Schema::dropIfExists('el_offline_reference');
    }
}
