<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElUnitTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //if (!Schema::hasTable('el_unit_type')) {
            Schema::create('el_unit_type', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->integer('created_by')->nullable()->default(2)->index();
                $table->integer('updated_by')->nullable()->default(2)->index();
                $table->integer('unit_by')->nullable()->index();
                $table->timestamps();
            });
        //}

        DB::table('el_unit_type')->insert(
            [
                [
                    'name' => 'Hội sở',
                ],
                [
                    'name' => 'Đơn vị kinh doanh',
                ],
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_unit_type');
    }
}
