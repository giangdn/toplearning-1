<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElTrainingPartnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('el_training_partner')) {
            Schema::create('el_training_partner', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('code', 150)->unique();
                $table->string('name');
                $table->string('people')->nullable();
                $table->string('address')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->integer('created_by')->nullable()->index();
                $table->integer('updated_by')->nullable()->index();
                $table->integer('unit_by')->nullable()->index();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_training_partner');
    }
}
