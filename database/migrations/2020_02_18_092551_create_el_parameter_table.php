<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElParameterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_parameter', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('tên thông số');
            $table->integer('type')->comment('Loại hoàn thành. 1_Khóa học, 2_Đánh giá, 3_Thi');
            $table->float('score')->default(0)->comment('điểm đạt');
            $table->timestamps();
        });

        for ($index = 1; $index <= 3; $index++) {
            DB::table('el_parameter')->insert([
                'name' => $index == 1 ? 'Khóa học' : ($index == 2 ? 'Đánh giá kỹ năng' : 'Thi kiến thức'),
                'type' => $index == 1 ? 1 : ($index == 2 ? 2 : 3),
                'score' => 0,
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
        Schema::dropIfExists('el_parameter');
    }
}
