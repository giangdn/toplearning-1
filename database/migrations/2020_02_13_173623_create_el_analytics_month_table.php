<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElAnalyticsMonthTable extends Migration
{
    public function up()
    {
        Schema::create('el_analytics_month', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('month', 7);
            $table->bigInteger('access')->default(0);
            $table->decimal('minute', 10, 2)->default(0);
            $table->timestamps();
            $table->unique(['user_id', 'month']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_analytics_month');
    }
}
