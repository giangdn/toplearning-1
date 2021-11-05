<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCertTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('el_cert')) {
            Schema::create('el_cert', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('certificate_code', 150)->unique();
                $table->string('certificate_name');
                $table->integer('status')->nullable()->default(1)->index();
                $table->integer('created_by')->nullable()->default(2)->index();
                $table->integer('updated_by')->nullable()->default(2)->index();
                $table->integer('unit_by')->nullable()->index();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('el_cert');
    }
}
