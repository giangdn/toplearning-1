<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElMailSignatureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_mail_signature', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('unit_id')->comment('id của el_unit có level 0');
            $table->longText('content')->nullable();
            $table->integer('created_by')->default(2);
            $table->integer('updated_by')->default(2);
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
        Schema::dropIfExists('el_mail_signature');
    }
}
