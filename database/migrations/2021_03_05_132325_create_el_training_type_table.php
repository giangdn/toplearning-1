<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElTrainingTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_training_type', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 150)->unique();
            $table->string('name');
            $table->integer('status')->default(0);
            $table->integer('created_by')->nullable()->default(2)->index();
            $table->integer('updated_by')->nullable()->default(2)->index();
            $table->timestamps();
        });

        DB::table('el_training_type')->insert(
            [
                [
                    'name' => 'Đào tạo trực tuyến',
                    'code' => 'DTEL',
                    'status' => 1,
                ],
                [
                    'status' => 1,
                    'name' => 'Đào tạo tập trung',
                    'code' => 'DTTT'
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
        Schema::dropIfExists('el_training_type');
    }
}
