<?php

namespace Modules\TrainingUnit\Http\Controllers;

use App\Permission;
use App\Profile;
use App\Models\Categories\Unit;
use G\Server\Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPermission;
use Modules\Quiz\Entities\QuizQuestion;

class QuizController extends Controller
{
    public function index()
    {
        return view('trainingunit::backend.quiz.index');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $user = Profile::find(Auth::id());
        //$managers = Permission::getIdUnitManagerByUser('module.training_unit');

        $query = Quiz::query();
        $query->select(['a.*']);
        $query->from('el_quiz AS a');
        $query->leftJoin('el_unit AS b', 'b.id', '=', 'a.unit_id');
        $query->where('b.code', '=', $user->unit_code);
        $query->whereNotNull('a.unit_id');

//        if ($managers) {
//            $query->whereIn('a.unit_id', $managers);
//        }

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('a.name', 'like', '%'. $search .'%');
                $sub_query->orWhere('a.code', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->result = '';
            if (userCan('training-unit-quiz-result')){
                $row->result = route('module.training_unit.quiz.result', ['id' => $row->id]);
            }
            $row->question = '';
            if (userCan('training-unit-quiz-add-question')) {
                $row->question = route('module.training_unit.quiz.question', ['id' => $row->id]);
            }
            $row->register_url = '';
            if (userCan('training-unit-quiz-register')){
                $row->register_url = route('module.training_unit.quiz.register', ['id' => $row->id]);
            }
            /*$row->user_secondary_url = '';
            if (QuizPermission::addUserSecondaryQuiz($row)){
                $row->user_secondary_url = route('module.training_unit.quiz.register.user_secondary', ['id' => $row->id]);
            }*/
            $row->export_url = '';
            if (userCan('training-unit-quiz-print-exam')){
                $row->export_url = route('module.training_unit.quiz.export_quiz', ['id' => $row->id]);
            }

            $row->edit_url = route('module.training_unit.quiz.edit', ['id' => $row->id]);
            $row->start_date = get_date($row->start_date, 'H:i d/m/Y');
            $row->end_date = get_date($row->end_date, 'H:i d/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = 0){
        $profile = Profile::find(\Auth::id());
        $unit = Unit::where('code', '=', $profile->unit_code)->first();
        if (empty($unit)){
            session()->put('error', 'Bạn chưa có đơn vị. Không thể tạo Kế hoạch khảo thí');
            session()->save();
            return redirect()->route('module.training_unit.quiz');
        }

        $controller = new \Modules\Quiz\Http\Controllers\BackendController();
        $controller->is_unit = $unit->id;
        return $controller->form($id);
    }

    public function exportQuiz($quiz_id = 0){
        $controller = new \Modules\Quiz\Http\Controllers\BackendController();
        return $controller->exportQuiz($quiz_id);
    }

    public function addQuestion($quiz_id = 0){
        $controller = new \Modules\Quiz\Http\Controllers\QuizQuestionController();
        return $controller->index($quiz_id);
    }

    public function register($quiz_id) {
        $controller = new \Modules\Quiz\Http\Controllers\RegisterController();
        return $controller->index($quiz_id);
    }

    public function registerForm($quiz_id)
    {
        $controller = new \Modules\Quiz\Http\Controllers\RegisterController();
        return $controller->form($quiz_id);
    }

    public function userSecondary($quiz_id) {
        $controller = new \Modules\Quiz\Http\Controllers\RegisterController();
        return $controller->indexSecondary($quiz_id);
    }

    public function userSecondaryForm($quiz_id)
    {
        $controller = new \Modules\Quiz\Http\Controllers\RegisterController();
        return $controller->formSecondary($quiz_id);
    }

    public function result($quiz_id)
    {
        $controller = new \Modules\Quiz\Http\Controllers\ResultController();
        return $controller->index($quiz_id);
    }

    public function saveIsOpen(Request $request){
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Kỳ thi',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        foreach($ids as $id){
            $model = Quiz::findOrFail($id);
            $model->is_open = $status;
            $model->save();
        }
    }

    public function saveStatus(Request $request){
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Kỳ thi',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        foreach($ids as $id){
            $model = Quiz::find($id);
            $model->status = $status;
            $model->save();
        }
    }

    public function saveViewResult(Request $request){
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Kỳ thi',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        foreach($ids as $id){
            $model = Quiz::findOrFail($id);
            $model->view_result = $status;
            $model->save();
        }
    }

    public function copyQuiz(Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Kỳ thi',
        ]);

        $ids = $request->input('ids', null);
        foreach($ids as $id){
            $model = Quiz::find($id);
            $newModel = $model->replicate();
            $newModel->code = $newModel->code . '_copy';
            $newModel->save();
            $quiz_ques = QuizQuestion::where('quiz_id', '=', $id)->get();

            foreach($quiz_ques as $item){
                $newQuizQues = $item->replicate();
                $newQuizQues->quiz_id = $newModel->id;
                $newQuizQues->save();
            }
        }
    }
    public function remove(Request $request) {
        $ids = $request->ids;
        Quiz::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }


}
