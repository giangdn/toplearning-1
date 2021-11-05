<?php
namespace Modules\Quiz\Imports;

use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionAnswer;
use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class QuestionImport implements ToModel, WithStartRow
{
    public $errors;
    protected $plat = 0;

    public function __construct($category_id)
    {
        $this->errors = [];
        $this->category_id = $category_id;
    }

    public function model(array $row)
    {
        $error = false;
        $index = (int) $row[0];

        if($index){
            $this->plat = 0;

            if(!isset($row[2])){
                $this->errors[] = 'Câu hỏi số <b>'. $index .'</b> chưa chọn loại';
                $error = true;
            }
            /*if ($row[2] == 2 && !isset($row[5])){
                $this->errors[] = 'Câu hỏi tự luận số <b>'. $index .'</b> chưa nhập đáp án gợi ý';
                $error = true;
            }*/

            /*$ques = Question::where('category_id', '=', $this->category_id)
                ->where('name', '=', (string)$row[1] )->first();
            if ($ques){
                $this->errors[] = 'Câu hỏi số <b>'. $index .'</b> đã tồn tại';
                $error = true;
                $this->plat = 1;
            }*/
        }

        if($error) {
            return null;
        }
        /*$feedbacks = explode('#', $row[5]);
        foreach ($feedbacks as $feedback) {
            $arr[] = $feedback;
        }*/

        if($index){
            Question::create([
                'name' => $row[1],
                'type' => $row[2] == 1 ? 'multiple-choise' : 'essay',
                'note' => $row[4] ? $row[4] : null,
                'category_id' => $this->category_id,
                'multiple' => $row[2] == 1 ? ((int)$row[3] ?? 1 ?? 0) : 0,
                'status' => 2,
                'feedback' => '',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'shuffle_answers' => $row[6] ? $row[6] : 0,
            ]);
        }else{
            if ($this->plat == 0){
                $question = Question::orderBy('id', 'DESC')->first();
                if ($row[1]) {
                    QuestionAnswer::create([
                        'question_id' => $question->id,
                        'title' => $row[1],
                        'correct_answer' => $question->multiple == 0 ? ((int) $row[3] ?? 1 ?? 0): 0,
                        'percent_answer' => $question->multiple == 1 ? $row[3] : 0,
                    ]);
                }
            }
        }
    }

    public function startRow(): int
    {
        return 2;
    }

}
