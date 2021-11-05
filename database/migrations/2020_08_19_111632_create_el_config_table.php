<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

            Schema::create('el_config', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('object')->nullable();
                $table->longText('value')->nullable();
            });

            DB::table('el_config')->updateOrInsert(['name' => 'logo'],
                [
                'value' => '2020/08/14/uzAXb4H25S_2020_08_14_16_28_14.png',
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_config');
    }
}
