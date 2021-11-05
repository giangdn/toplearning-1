<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElProfleStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('el_profile_status')) {
            Schema::create('el_profile_status', function (Blueprint $table) {
                $table->bigInteger('id');
                $table->string('code')->nullable();
                $table->string('name')->comment('1: đang làm việc, 2: thử việc, 3:tạm hoãn, 4: nghỉ việc');
                $table->integer('status')->nullable();
                $table->timestamps();
            });
        }

        DB::table('el_profile_status')->insert(
            [
                [
                    'id' => 0,
                    'name' => 'Nghỉ việc',
                ],
                [
                    'id' => 1,
                    'name' => 'Đang làm việc',
                ],
                [
                    'id' => 2,
                    'name' => 'Thử việc',
                ],
                [
                    'id' => 3,
                    'name' => 'Tạm hoãn',
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
        Schema::dropIfExists('el_profile_status');
    }
}
