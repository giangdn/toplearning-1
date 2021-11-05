<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElLanguagesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         DB::table('el_languages')->insert([
            [
                'groups_id' => '1',
				'pkey' => 'languages',
				'content' => 'Ngôn ngữ',
				'content_en' => 'Languages',
            ],
            [
                'groups_id' => '1',
				'pkey' => 'addnew',
				'content' => 'Thêm mới',
				'content_en' => 'Add new',
            ]           
        ]);
		
		 DB::table('el_languages_groups')->insert([
            [
                'name' => 'Core',
                'slug' => 'core'
            ]
              
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
