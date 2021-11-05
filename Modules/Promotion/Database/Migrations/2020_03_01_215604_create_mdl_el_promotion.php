<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMdlElPromotion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_promotion', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->string('code', 150)->unique();
            $table->string('name');
            $table->integer('point');
            $table->string('images')->nullable();
            $table->timestamp('period')->nullable();
            $table->text('rules')->nullable();
            $table->integer('amount')->default(1);
            $table->integer('promotion_group')->index();
            $table->text('contact')->nullable();
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('el_promotion');
    }
}
