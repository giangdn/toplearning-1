<?php

namespace Modules\Quiz\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class QuizDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call([
            QuestionCategoryTableSeeder::class,
            QuestionTableSeeder::class,
        ]);
    }
}
