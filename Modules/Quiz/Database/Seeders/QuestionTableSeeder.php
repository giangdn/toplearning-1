<?php

namespace Modules\Quiz\Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory;

class QuestionTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();
        foreach (range(1,100) as $index) {
            $type = $faker->randomElement(['essay', 'multiple-choise']);
            $multiple = $faker->numberBetween(0,1);
            if ($type == 'essay') {
                $multiple = 0;
            }

            \DB::table('el_question')->insert([
                'name' => 'Câu hỏi'.($multiple == 1 ? ' chọn nhiều': '').' #'. ($index),
                'type' => $type,
                'category_id' => $faker->randomElement(\Modules\Quiz\Entities\QuestionCategory::pluck('id')->toArray()),
                'multiple' => $multiple,
                'status' => 1,
                'created_by' => 2,
                'updated_by' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $questions = \DB::table('el_question')->where('type', '=', 'multiple-choise')->get();
        foreach ($questions as $question) {
            $correct = false;
            $num_anwser = $faker->numberBetween(3,5);

            foreach (range(1,$num_anwser) as $index) {
                $correct_answer = 0;
                if (!$correct) {
                    $correct_answer = $faker->numberBetween(0,1);
                }

                if ($correct_answer == 1 && $question->multiple == 0) {
                    $correct = true;
                }

                \DB::table('el_question_answer')->insert([
                    'question_id' => $question->id,
                    'title' =>  'Câu trả lời '. ($correct_answer == 1 ? 'đúng ' : '') .'#'. ($index),
                    'correct_answer' => $correct_answer,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
