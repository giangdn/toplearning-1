<?php
namespace Modules\TrainingUnit\Imports;

use Modules\TrainingUnit\Entities\ProposedQuestion;
use Modules\TrainingUnit\Entities\ProposedQuestionAnswer;
use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProposedQuestionImport implements ToModel, WithStartRow
{
    public $errors;

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
            if(!isset($row[2])){
                $this->errors[] = 'Câu hỏi số <b>'. $index .'</b> chưa chọn loại';
                $error = true;
            }
        }

        if($error) {
            return null;
        }

        if($index){
            ProposedQuestion::create([
                'name' => $row[1],
                'type' => $row[2] == 1 ? 'multiple-choise' : 'essay',
                'category_id' => $this->category_id,
                'multiple' => (int) $row[3] ?? 1 ?? 0,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
        }else{

            ProposedQuestionAnswer::create([
                'title' => $row[1],
                'question_id' => 1,
                'is_text' => (int) $row[2] ?? 1 ?? 0,
                'correct_answer' => (int) $row[3] ?? 1 ?? 0,
            ]);
        }
    }
    
    public function startRow(): int
    {
        return 2;
    }

}