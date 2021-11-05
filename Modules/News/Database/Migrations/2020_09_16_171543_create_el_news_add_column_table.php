<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElNewsAddColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('el_news', function (Blueprint $table) {
            $table->integer('hot')->default(0)->change();
            $table->integer('user_view')->nullable()->change()->comment('người xem cuối');
            $table->dateTime('view_time')->nullable()->change()->comment('thời gian bấm xem');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::table('el_news', function (Blueprint $table) {
            $table->dropColumn('hot');
            $table->dropColumn('user_view');
            $table->dropColumn('view_time');
        });*/
    }
}
