<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory;
use Illuminate\Support\Str;

class OfflineTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
        $faker = Faker\Factory::create();

        //
        foreach (range(1,20) as $index) {
            $training_program_id = $faker->randomElement(\App\Models\Categories\TrainingProgram::where('status', 1)->pluck('id')->toArray());

            $level_subject_id = $faker->randomElement(\App\Models\Categories\LevelSubject::where('status', 1)
                ->where('training_program_id', '=', $training_program_id)
                ->pluck('id')->toArray());

            $subject_id = $faker->randomElement(\App\Models\Categories\Subject::where('status', 1)
                ->where('level_subject_id', '=', $level_subject_id)
                ->where('training_program_id', '=', $training_program_id)
                ->pluck('id')->toArray());

            DB::table('el_offline_course')->insert([
                'code'=> Str::random(10),
                'name'=> 'Khóa học offline #'. $index,
                'plan_detail_id'=> 1,
                'isopen'=> 1,
                'status'=> 1,
                'description'=>$faker->paragraph(30),
                'start_date'=> date('Y-m-d H:i:s'),
                'end_date'=> date('Y-m-d H:i:s'),
                'max_student' => 5,
                'register_deadline'=> date('Y-m-d H:i:s'),
                'created_by'=> 2,
                'updated_by'=> 2,
                'subject_id'=> $subject_id,
                'level_subject_id'=> $level_subject_id,
                'training_location_id'=>1,
                'training_area_id'=>1,
                'training_partner_id'=>1,
                'training_program_id'=>$training_program_id,
                'training_form_id' => $faker->randomElement(\App\Models\Categories\TrainingForm::pluck('id')->toArray()),
                'content'=> '',
                'views' => 1,
                'category_id'=> 1,
                'course_time'=> 5,
                'num_lesson'=> 5,
                'action_plan'=> 0,
                'cert_code'=>0,
                'has_cert'=> 0,
                'training_unit' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
