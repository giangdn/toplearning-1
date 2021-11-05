<?php

namespace Modules\Quiz\Console;

use App\Automail;
use Illuminate\Console\Command;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;

class QuizFinal extends Command
{
    protected $signature = 'mail:quiz_final';

    protected $description = 'Gửi email chấm điểm kỳ thi cho giáo viên 10 phút/lần';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $datas = QuizPart::with('quizTeachers','quizTeachers.teacher:id,user_id,name,email')
            ->whereRaw('ROUND(('.unix_timestamp_sql().' - '.unix_timestamp_sql("end_date").')/60,0) BETWEEN 0 and  10')->has('quizTeachers')->get();
        foreach ($datas as $data) {
            $quiz = Quiz::find($data->quiz_id);
            foreach ($data->quizTeachers as $quiz_teacher) {
                $signature = getMailSignature($quiz_teacher->teacher->user_id);
                $params = [
                    'signature' => $signature,
                    'name' => $quiz_teacher->teacher->name,
                    'quiz_code' => $quiz->code,
                    'quiz_name' => $quiz->name,
                    'quiz_part_name' => $data->name,
                    'start_quiz_part' => $data->start_date,
                    'end_quiz_part' => $data->end_date,
                    'url' => route('module.quiz.grading.user', ['quiz_id' => $quiz->id])
                ];
                $automail = new Automail();
                $automail->template_code = 'grading_quiz';
                $automail->params = $params;
                $automail->users = [$quiz_teacher->teacher->user_id];
                $automail->check_exists = true;
                $automail->check_exists_status = 0;
                $automail->object_id = $quiz_teacher->id;
                $automail->object_type = 'grading_quiz';
                $automail->addToAutomail();
            }
        }
        $this->info('Cập nhật thành công');
    }
}
