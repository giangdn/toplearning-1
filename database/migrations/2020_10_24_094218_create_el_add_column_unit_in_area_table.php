<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElAddColumnUnitInAreaTable extends Migration
{
    public function up()
    {
        Schema::table('el_area', function (Blueprint $table) {
            if (!Schema::hasColumn('el_area', 'unit_id')) {
                $table->bigInteger('unit_id')->nullable();
            }
        });
    }
    
    public function down()
    {
    
    }
}
