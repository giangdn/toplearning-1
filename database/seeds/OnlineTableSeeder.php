<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory;
use Illuminate\Support\Str;

class OnlineTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        foreach (range(1,25) as $index) {
            $training_program_id = $faker->randomElement(\App\Models\Categories\TrainingProgram::where('status', 1)->pluck('id')->toArray());

            $level_subject_id = $faker->randomElement(\App\Models\Categories\LevelSubject::where('status', 1)
                ->where('training_program_id', '=', $training_program_id)
                ->pluck('id')->toArray());

            $subject_id = $faker->randomElement(\App\Models\Categories\Subject::where('status', 1)
                ->where('level_subject_id', '=', $level_subject_id)
                ->where('training_program_id', '=', $training_program_id)
                ->pluck('id')->toArray());

            DB::table('el_online_course')->insert([
                'code'=> 'online'. $index,
                'name'=> 'Khóa học online #'. $index,
                'moodlecourseid'=> 1,
                'isopen'=> 1,
                'status'=> 1,
                'start_date'=> date('Y-m-d H:i:s'),
                'end_date'=> null,
                'created_by'=> 2,
                'updated_by'=> 2,
                'category_id'=> 1,
                'description'=> $faker->paragraph(30),
                'subject_id' => $subject_id,
                'level_subject_id' => $level_subject_id,
                'plan_detail_id'=> 1,
                'training_program_id'=> $training_program_id,
                'register_deadline'=> date('Y-m-d H:i:s'),
                'content'=> '',
                'course_time'=> $faker->numberBetween(5,15),
                'num_lesson'=> $faker->numberBetween(5,15),
                'action_plan'=> 0,
                'cert_code'=> 0,
                'has_cert'=> 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
