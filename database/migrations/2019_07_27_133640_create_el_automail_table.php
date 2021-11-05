<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElAutomailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_automail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('list_mail');
            $table->text('params');
            $table->string('template_code');
            $table->bigInteger('object_id')->comment('id loại');
            $table->string('object_type')->comment('code el_mail_object_type');
            $table->tinyInteger('limited')->default(0)->comment('đã đạt giới hạn mail');
            $table->tinyInteger('priority')->default(1)->comment('độ ưu tiên');
            $table->dateTime('sendtime')->nullable();
            $table->text('error')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('created_by')->default(1)->nullable();
            $table->integer('updated_by')->default(1)->nullable();
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
        Schema::dropIfExists('el_automail');
    }
}
