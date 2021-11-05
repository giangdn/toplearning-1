<?php

namespace Modules\Quiz\Http\Controllers\Backend;

use App\Automail;
use App\Profile;
use App\ProfileView;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\PermissionApproved\Entities\ApprovedModelTracking;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizUserSecondary;

class QuizController extends Controller
{
    public function approve(Request $request){
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Kỳ thi',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        $note = $request->input('status', null);
        foreach($ids as $id){
            (new ApprovedModelTracking())->updateApprovedTracking(Quiz::getModel(),$id,$status,$note);

            if ($status == 1){
                $this->updateSendEmailQuiz($id);
            }
        }
    }
    public function updateSendEmailQuiz($quiz_id)
    {
        $quiz = Quiz::with('type')->find($quiz_id);
        $quizPartUsers = QuizRegister::with('quizparts:id,name,start_date,end_date,el_quiz_register.user_id,el_quiz_register.type')->where('quiz_id',$quiz_id)->get()->pluck('quizparts')->flatten();

        foreach ($quizPartUsers as $quizPartUser) {
            if ($quizPartUser->type == 1)
                $profile = ProfileView::query()->where('user_id', $quizPartUser->user_id)->first();
            else
                $profile = QuizUserSecondary::query()->where('id',$quizPartUser->user_id)->first();

            $signature = getMailSignature($quizPartUser->user_id, $quizPartUser->type);
            $params = [
                'signature' => $signature,
                'gender' => $quizPartUser->type == 1 ? ($profile->gender=='1'?'Anh':'Chị') : 'Anh/Chị',
                'full_name' => $quizPartUser->type == 1 ? $profile->full_name : $profile->name,
                'quiz_name' => $quiz->name,
                'quiz_type' => $quiz->type? $quiz->type->name:'',
                'quiz_part_name' => $quizPartUser->name,
                'start_quiz_part' => get_datetime($quizPartUser->start_date),
                'end_quiz_part' => get_datetime($quizPartUser->end_date),
                'quiz_time' => $quiz->limit_time,
                'pass_score' => $quiz->pass_score,
                'url' => route('module.quiz.doquiz.index', ['quiz_id' => $quiz_id,'part_id'=>$quizPartUser->id])
            ];
            $user_id = [$quizPartUser->user_id];
            $this->saveEmailQuizRegister($params,$user_id,$quizPartUser->id,$quizPartUser->type);
        }
    }
    public function saveEmailQuizRegister(array $params,array $user_id,int $part_id, int $user_type)
    {
        $automail = new Automail();
        $automail->template_code = 'quiz_registerd';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->user_type = $user_type;
        $automail->check_exists = true;
        $automail->check_exists_status = 0;
        $automail->object_id = $part_id;
        $automail->object_type = 'approve_quiz';
        $automail->addToAutomail();
    }
}
