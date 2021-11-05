<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElGuideTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_guide', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('attach');
            $table->integer('created_by')->nullable()->default(2);
            $table->integer('updated_by')->nullable()->default(2);
            $table->integer('unit_by')->nullable();
            $table->integer('type');
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
        Schema::dropIfExists('el_guide');
    }
}
