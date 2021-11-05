<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElPlanAppStatusTable extends Migration
{
    public function up()
    {
        Schema::create('el_plan_app_status', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name',250)->comment('trạng thái');
            $table->timestamps();
        });
        
        DB::table('el_plan_app_status')->insert(
            [
                [
                    'id' => 1,
                    'name' => 'Đã lập kế hoạch',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 2,
                    'name' => 'Đã duyệt kế hoạch',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 3,
                    'name' => 'Từ chối',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 4,
                    'name' => 'Đã đánh giá',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 5,
                    'name' => 'Đã duyệt đánh giá',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]
        );
    }
    
    public function down()
    {
        Schema::dropIfExists('el_plan_app_status');
    }
}
