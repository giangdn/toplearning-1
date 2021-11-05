<?php

namespace Modules\Quiz\Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;

class QuizTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        foreach (range(1,3) as $index) {
            \DB::table('el_quiz')->insert([
                'code'=> 'quiz'. $index,
                'name'=> 'Kỳ thi #'. $index,
                'is_open'=> 1,
                'status'=> 1,
                'limit_time' => 60,
                'quiz_type' => $faker->numberBetween(1,3),
                'questions_perpage' => 5,
                'grade_methor' => $faker->numberBetween(1,3),
                'created_by'=> 2,
                'updated_by'=> 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $quizs = \DB::table('el_quiz')->get();
        foreach ($quizs as $quiz) {
            foreach (range(1,2) as $index) {
                $end_date = time() + 432000;
                \DB::table('el_quiz_part')->insert([
                    'quiz_id'=> $quiz->id,
                    'name'=> 'Ca '. $index,
                    'start_date'=> date('Y-m-d 00:00:00'),
                    'end_date'=> date('Y-m-d H:i:s', $end_date),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            foreach (range(1,5) as $index) {
                $random = $faker->numberBetween(0,1);
                $question = null;
                $category = null;

                if ($random == 1) {
                    $category = $faker->randomElement(\Modules\Quiz\Entities\QuestionCategory::where('status', '=', 1)
                        ->whereNotIn('id', function ($subquery) use ($quiz) {
                            $subquery->select(['qcategory_id'])
                                ->from('el_quiz_question')
                                ->where('quiz_id', '=', $quiz->id)
                                ->where('random', '=', 1);
                        })
                        ->pluck('id')
                        ->toArray());
                }
                else {
                    $question = $faker->randomElement(\Modules\Quiz\Entities\Question::where('status', '=', 1)
                        ->whereNotIn('id', function ($subquery) use ($quiz) {
                            $subquery->select(['question_id'])
                                ->from('el_quiz_question')
                                ->where('quiz_id', '=', $quiz->id)
                                ->where('random', '=', 0);
                        })
                        ->pluck('id')
                        ->toArray());
                }

                \DB::table('el_quiz_question')->insert([
                    'quiz_id'=> $quiz->id,
                    'question_id'=> $question,
                    'qcategory_id'=> $category,
                    'random'=> $random,
                    'num_order' => $index,
                    'max_score' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
