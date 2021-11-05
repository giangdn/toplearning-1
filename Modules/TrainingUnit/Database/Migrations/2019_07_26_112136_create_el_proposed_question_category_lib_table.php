<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElProposedQuestionCategoryLibTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_proposed_question_category_lib', function (Blueprint $table) {
            $table->bigInteger('pqc_id')->comment('Danh mục câu hỏi đề xuất');
            $table->bigInteger('qcl_id')->comment('Danh mục ngân hàng câu hỏi');
            $table->primary(['pqc_id', 'qcl_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_proposed_question_category_lib');
    }
}
