<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_area', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 150)->unique();
            $table->string('name');
            $table->integer('level');
            $table->string('parent_code', 150)->nullable()->index();
            $table->bigInteger('unit_id')->nullable()->index();
            $table->integer('status')->default(1);
            $table->timestamps();
        });

        for ($index = 1; $index <= 5; $index++) {
            DB::table('el_area')->insert([
                'code' => 'area' . ($index),
                'name' => ($index == 1) ? 'Việt Nam' : 'area ' . ($index),
                'parent_code' => ($index == 1) ? null : 'area' . ($index - 1),
                'status' => 1,
                'level' => $index,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_area');
    }

    public function deleteUnit($parent_code = null) {
        $units = \App\Models\Categories\Area::where('parent_code', '=', $parent_code)->get();
        foreach ($units as $unit) {
            $this->deleteUnit($unit->code);
            \App\Models\Categories\Area::where('code', '=', $unit->code)->delete();
        }
    }
}
