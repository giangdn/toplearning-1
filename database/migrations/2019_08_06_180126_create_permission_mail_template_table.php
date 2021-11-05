<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionMailTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*DB::table('el_permission')->insert(
            [
                [
                    'code' => 'backend_mail_template',
                    'name' => 'Mail template',
                    'unit_permission' => 0
                ]
            ]
        );
        DB::table('el_permission')->insert([
            [
                'code' => 'backend.mailtemplate',
                'name' => 'Xem mẫu mail',
                'parent_code' => 'backend_mail_template',
                'extend' => null,
            ],
        ]);*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_mail_template');
    }
}
