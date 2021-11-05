<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateActivityScormAttemptsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('el_activity_scorm_attempts', 'lesson_location')) {
            Schema::table('el_activity_scorm_attempts', function (Blueprint $table) {
                $table->string('lesson_location', 100)->nullable();
            });
        }
    
        if (!Schema::hasColumn('el_activity_scorm_attempts', 'suspend_data')) {
            Schema::table('el_activity_scorm_attempts', function (Blueprint $table) {
                $table->text('suspend_data')->nullable();
            });
        }
    }
    
    public function down()
    {
    
    }
}
