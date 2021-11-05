<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableElOfflineAttendanceEdit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('el_offline_attendance', function (Blueprint $table) {
            $table->integer('absent_id')->default(0)->nullable();
			$table->integer('absent_reason_id')->default(0)->nullable();
			$table->integer('discipline_id')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       if (Schema::hasColumn('el_offline_attendance', 'absent_id'))
		{
			Schema::table('el_offline_attendance', function (Blueprint $table)
			{
				$table->dropColumn('absent_id');
			});
		}
		if (Schema::hasColumn('el_offline_attendance', 'absent_reason_id'))
		{
			Schema::table('el_offline_attendance', function (Blueprint $table)
			{
				$table->dropColumn('absent_reason_id');
			});
		}
		if (Schema::hasColumn('el_offline_attendance', 'discipline_id'))
		{
			Schema::table('el_offline_attendance', function (Blueprint $table)
			{
				$table->dropColumn('discipline_id');
			});
		}
		
    }
}
