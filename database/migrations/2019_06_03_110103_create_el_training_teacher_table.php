<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElTrainingTeacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('el_training_teacher')) {
            Schema::create('el_training_teacher', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('user_id')->nullable();
                $table->bigInteger('teacher_type_id')->nullable();
                $table->bigInteger('training_partner_id')->nullable();
                $table->string('code', 150);
                $table->string('name');
                $table->string('email');
                $table->string('phone');
                $table->string('account_number')->nullable();
                $table->integer('status')->default(1);
                $table->integer('type')->default(1)->comment('1: Nội bộ, 2: Bên ngoài');
                $table->integer('created_by')->nullable()->index();
                $table->integer('updated_by')->nullable()->index();
                $table->integer('unit_by')->nullable()->index();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_training_teacher');
    }
}
