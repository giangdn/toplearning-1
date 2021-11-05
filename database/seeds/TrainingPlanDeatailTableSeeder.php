<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory;
use Illuminate\Support\Str;

class TrainingPlanDeatailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $plan_id = $faker->randomElement(\Modules\TrainingPlan\Entities\TrainingPlan::where('status', 1)->pluck('id')->toArray());

        $training_program_id = $faker->randomElement(\App\Models\Categories\TrainingProgram::where('status', 1)->pluck('id')->toArray());

        $level_subject_id = $faker->randomElement(\App\Models\Categories\LevelSubject::where('status', 1)
            ->where('training_program_id', '=', $training_program_id)->pluck('id')->toArray());

        $subject_id = $faker->randomElement(\App\Models\Categories\Subject::where('status', 1)
            ->where('training_program_id', '=', $training_program_id)
            ->where('level_subject_id', '=', $level_subject_id)
            ->pluck('id')->toArray());

        if (!DB::table('el_training_plan_detail')->exists()) {
            foreach (range(1,100) as $index) {
                DB::table('el_training_plan_detail')->insert([
                    'plan_id' => $plan_id,
                    'subject_id' => $subject_id,
                    'level_subject_id' => $level_subject_id,
                    'training_program_id' => $training_program_id,
                    'note' => $faker->sentence(),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
