<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

class CreateAddLastOnlineToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('user', function (Blueprint $table) {
            if (!Schema::hasColumn('user', 'last_online')) {
                $table->dateTime('last_online')->nullable();
            }
        });
    }
    
    public function down()
    {
    
    }
}
