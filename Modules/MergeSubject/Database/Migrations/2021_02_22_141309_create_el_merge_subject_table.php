<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElMergeSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_merge_subject', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('subject_old',4000);
            $table->string('subject_new');
            $table->integer('subject_old_complete')->nullable();
            $table->integer('type')->comment('1: merge, 2: split');
            $table->integer('status')->nullable()->comment('null: Chưa duyệt,1: Đã duyệt, 0: Từ chối');
            $table->integer('approved_by')->nullable();
            $table->integer('approved_date')->nullable();
            $table->integer('number_merge_completed')->nullable();
            $table->integer('number_merge_subject')->nullable();
            $table->string('note')->nullable();
            $table->integer('pending')->nullable()->comment('1 pedding, 0 đã gộp/tách');
            $table->integer('merge_option')->nullable()->comment('1: số lượng hoàn thành, 2: chuyên đề cụ thể');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('unit_by')->nullable();
            $table->tinyInteger('flag')->index()->nullable()->comment('1 đã chạy cron');
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
        Schema::dropIfExists('el_merge_subject');
    }
}
