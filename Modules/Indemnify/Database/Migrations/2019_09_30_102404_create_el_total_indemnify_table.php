<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElTotalIndemnifyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_total_indemnify', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->decimal('total_indemnify',18,2)->comment('Tổng tiền bồi hoàn');
            $table->decimal('percent',18,2)->comment('Phần trăm miễn giảm');
            $table->decimal('exemption_amount',18,2)->comment('Số tiền được giảm');
            $table->decimal('total_cost',18,2)->comment('Tiền bồi hoàn cuối cùng');
            $table->dateTime('day_off')->nullable()->comment('Ngày nghỉ');
            $table->integer('compensated')->default(0)->comment('Đã bồi hoàn');
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
        Schema::dropIfExists('el_total_indemnify');
    }
}
