<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory;
use Illuminate\Support\Str;

class ForumThreatTableSeeder extends Seeder
{
    public function run()
    {

        $faker = Faker\Factory::create();
        foreach (range(1,20) as $index) {
            DB::table('el_forum_thread')->insert([
                'title'=> 'Bài viết #'.$index,
                'content' => $faker->paragraph(50),
                'forum_id' => $faker->randomElement(\Modules\Forum\Entities\Forum::pluck('id')->toArray()),
                'created_by' => 2,
                'updated_by' => 2,
                'status' => 1,
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),

            ]);
        }

        foreach (range(1,30) as $index) {
            DB::table('el_forum_comment')->insert([
                'comment'=> $faker->paragraph(50),
                'thread_id' => $faker->randomElement(\Modules\Forum\Entities\ForumThread::pluck('id')->toArray()),
                'created_by' => 2,
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ]);
        }
    }
}
