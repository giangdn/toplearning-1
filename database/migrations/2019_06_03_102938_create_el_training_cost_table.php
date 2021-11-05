<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElTrainingCostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //if (!Schema::hasTable('el_training_cost')) {
        Schema::create('el_training_cost', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->integer('created_by')->nullable()->index();
                $table->integer('updated_by')->nullable()->index();
                $table->integer('unit_by')->nullable()->index();
                $table->integer('type')->default(1)->comment('1: Chi phí tổ chức, 2: Chi phí phòng đào tạo, 3: Chi phí đào tạo bên ngoài, 4: Chi phí giảng viên, 5: Chi phí học viện');
                $table->timestamps();
            });
        //}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_training_cost');
    }
}
