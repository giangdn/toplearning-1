<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElUnitTable extends Migration
{
    public function up()
    {
        Schema::create('el_unit', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 150)->unique();
            $table->string('name');
            $table->integer('level');
            $table->string('parent_code', 150)->index()->nullable();
            $table->tinyInteger('status')->index();
            $table->string('email')->nullable()->comment('email của đơn vị');
            $table->bigInteger('type')->index()->nullable()->comment('table el_unit_type');
            $table->string('note1')->nullable();
            $table->string('note2')->nullable();
            $table->integer('created_by')->nullable()->default(2)->index();
            $table->integer('updated_by')->nullable()->default(2)->index();
            $table->integer('area_id')->nullable()->index();
            $table->timestamps();
        });

        /*for ($index = 1; $index <= 7; $index++) {
            DB::table('el_unit')->insert([
                'code' => 'test' . ($index),
                'name' => 'Test ' . ($index),
                'parent_code' => ($index == 1) ? null: 'test' . ($index - 1),
                'email' => 'test'.($index).'@gmail.com',
                'status' => 1,
                'type' => null,
                'level' => $index,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }*/
    }

    public function down()
    {
        Schema::dropIfExists('el_unit');
    }
}
