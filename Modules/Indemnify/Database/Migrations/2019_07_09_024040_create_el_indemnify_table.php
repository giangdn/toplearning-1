<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElIndemnifyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_indemnify', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->comment('mã user id');
            $table->bigInteger('course_id')->comment('mã khóa học');
            $table->integer('commit_date')->nullable()->comment('Tháng cam kết');
            $table->integer('date_diff')->nullable()->comment('Số tháng còn lại');
            $table->decimal('commit_amount',18,2)->nullable()->comment('Số tiền cam kết');
            $table->decimal('exemption_amount',18,2)->nullable()->comment('Số tiền miễn giảm');
            $table->decimal('cost_student', 18, 2)->nullable()->comment('Chi phí học viên');
            $table->decimal('course_cost', 18, 2)->nullable()->comment('Chi phí đào tạo');
            $table->boolean('compensated')->nullable()->comment('Đã được bồi thường');
            $table->decimal('cost_indemnify', 18,2)->nullable()->comment('Chi phí bồi hoàn');
            $table->float('coefficient')->nullable()->comment('hệ số K');
            $table->string('contract')->nullable()->comment('Số hợp đồng cam kết');
            $table->bigInteger('created_by')->nullable()->default(2);
            $table->bigInteger('updated_by')->nullable()->default(2);
            $table->integer('unit_by')->nullable();
            $table->string('calculator')->nullable();
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
        Schema::dropIfExists('el_indemnify');
    }
}
