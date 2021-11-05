<?php

namespace Modules\Quiz\Console;

use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Modules\Quiz\Entities\QuizAttemptGrade;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizUpdateAttempts;
use Modules\Quiz\Http\Helpers\AttemptGrade;

class AttemptComplete extends Command
{
    protected $signature = 'attempt:complete {attempt?}';

    protected $description = 'Quiz complete attempt.';

    protected $expression = "* * * * *";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $attempt_id = $this->argument('attempt');
        $query = QuizAttempts::query();

        if ($attempt_id) {
            $query->whereId($attempt_id);
        }
        else {
            $query->where('state', '=', 'completed')
                ->where('sumgrades', '<=', 0)
                ->whereNotExists(function (Builder $builder) {
                    $builder->select(['id'])
                        ->from('el_quiz_attempt_grades')
                        ->whereColumn('attempt_id', '=', 'el_quiz_attempts.id');
                });
        }

        $query->limit(1);
        $rows = $query->get();

        foreach ($rows as $row) {
            $this->info('Grade attempt: ' . $row->id);

            $grade = QuizAttemptGrade::firstOrNew(['attempt_id' => $row->id]);
            $grade->status = 2;
            $grade->save();

            $attempt_grade = new AttemptGrade($row);
            $score = $attempt_grade->getGrade();

            $row->update([
                'sumgrades' => $score,
            ]);

            $grade->update([
                'status' => 1,
            ]);

            $attempt = $row->getTemplateData();

            $update_attempt = QuizUpdateAttempts::where('attempt_id', '=', $row->id)
                ->where('quiz_id', '=', $row->quiz_id)
                ->where('part_id', '=', $row->part_id)
                ->where('user_id', '=', $row->user_id)
                ->where('type', '=', $row->type);

            if ($update_attempt->exists()){
                $update_attempt->update([
                    'categories' => json_encode($attempt['categories']),
                    'questions' => json_encode($attempt['questions']),
                    'score' => $score,
                    'status' => 1
                ]);
            }else{
                $update_attempt = new QuizUpdateAttempts();
                $update_attempt->attempt_id = $row->id;
                $update_attempt->quiz_id = $row->quiz_id;
                $update_attempt->part_id = $row->part_id;
                $update_attempt->user_id = $row->user_id;
                $update_attempt->type = $row->type;
                $update_attempt->categories = json_encode($attempt['categories']);
                $update_attempt->questions = json_encode($attempt['questions']);
                $update_attempt->score = $score;
                $update_attempt->status = 1;
                $update_attempt->save();
            }

            $this->info('Attempt: ' . $row->id . ' - Grade: ' . $score);
        }
    }
}
