<?php

namespace Modules\Quiz\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizNoteByUserSecond;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizType;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        $quiz_types = QuizType::get();

        $user_id = Quiz::getUserId();
        $user_type = Quiz::getUserType();
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $search = $request->input('search','');
        $start_date = $request->input('fromdate');
        $end_date = $request->input('todate');
        $quiz_type = $request->input('quiz_type');

        $query = QuizPart::query();
        $query->select([
            'a.id',
            'a.quiz_type',
            'a.name AS quiz_name',
            'a.view_result',
            'a.img',
            'b.id AS part_id',
            'b.start_date AS start_date',
            'b.end_date AS end_date',
        ]);
        $query->from('el_quiz AS a')
            ->join('el_quiz_part AS b', function ($subquery) use ($user_id, $user_type) {
                $subquery->on('b.quiz_id', '=', 'a.id')
                    ->whereIn('b.id', function ($subquery2) use ($user_id, $user_type) {
                        $subquery2->select(['part_id'])
                            ->from('el_quiz_register')
                            ->where('user_id', '=', $user_id)
                            ->where('type', '=', $user_type)
                            ->whereColumn('quiz_id', '=', 'a.id');
                    });
            })
            ->where('a.status', '=', 1)
            ->where('a.is_open', '=', 1)
            ->where(function ($sub){
                $sub->orWhere('a.quiz_type', '=', 3);
                $sub->orWhereIn('a.id', function ($subquery){
                    $subquery->select(['quiz_id'])
                        ->from('el_offline_course')
                        ->whereNotNull('quiz_id')
                        ->where('status', '=', 1)
                        ->where('isopen', '=', 1);
                });
            })
            ->whereIn('a.id', function ($subquery) use ($user_id, $user_type){
                $subquery->select(['quiz_id'])
                    ->from('el_quiz_register')
                    ->where('user_id', '=', $user_id)
                    ->where('type', '=', $user_type);
            });

        if ($search){
            $query->where('a.name', 'like', '%'.$search.'%');
        }

        if ($start_date) {
            $query->where('b.start_date', '>=', date_convert($start_date));
        }

        if ($end_date) {
            $query->where('b.start_date', '<=', date_convert($end_date));
        }

        if($quiz_type) {
            $query->where('a.quiz_type', '=', $quiz_type);
        }

        $query->orderBy($sort, $order);

        if(!$search && !$start_date && !$end_date && !$quiz_type) {
            $check_search = 0;
            $rows = $query->paginate(20);
        } else {
            $check_search = 1;
            $rows = $query->get();
        }

        foreach ($rows as $row) {
            $row->count_downt = $row->start_date;

            if ($row->end_date && $row->end_date < date('Y-m-d H:i:s')){
                $row->closed = 1;
            }

			if ($row->start_date <= date('Y-m-d H:i:s')){
                $row->goquiz_url = route('module.quiz.doquiz.index', [
                    'quiz_id' => $row->id,
                    'part_id' => $row->part_id,
                ]);
            }else{
                $row->goquiz_url = '';
            }

            $row->start_date = get_date($row->start_date, 'H:i d/m/Y');

			$row->end_date = get_date($row->end_date, 'H:i d/m/Y');

            $status = Quiz::getStatusUser($row->id);

            $row->review_link = route('module.quiz.doquiz.index', [$row->id, $row->part_id]);

            $row->image = image_file($row->img);

            $row->time_quiz = 1;

            if($status == 0) {
                $row->status = '<span class="text-muted">'. trans("app.not_tested") .'</span>';
            } else if ($status == 1) {
                $row->status = '<span class="text-success">'. trans("app.completed") .'</span>';
            } else {
                $row->status = '<span class="text-info">'. trans("app.exam_taking") .'</span>';
            }

            if ($row->closed == 1) {
                if($row->view_result == 1){
                    $row->link = '<a href="' . $row->review_link . '" class="btn btn-info"> '. trans("app.review") .'</a>';
                }else{
                    $row->link = '<button class="btn btn-danger">'. trans("app.exams_ended") .'</button>';
                }
            }
            else {
                if ($status == 1) {
                    $row->link = '<a href="' . $row->review_link . '" class="btn btn-info"> '. trans("app.review") .'</a>';
                }
                else if($row->goquiz_url){
                    $row->link = '<a href="' . $row->goquiz_url . '" class="btn btn-info">'. trans("app.goquiz") .'</a>';
                }
                else{
                    $row->time_quiz = 0;
                    $row->link = '<button class="btn btn-info notify-goquiz">Bài thi chưa tới giờ</button>';
                }
            }
        }

        return view('quiz::frontend.index', [
            'quizs' => $rows,
            'quiz_types' => $quiz_types,
            'check_search' => $check_search
        ]);
    }

    public function saveNoteQuiz(Request $request){
        $user_id = Quiz::getUserId();
        $quiz_id = $request->quiz_id;
        $title = $request->title;
        $content = $request->post('content');

        $model = new QuizNoteByUserSecond();
        $model->quiz_id = $quiz_id;
        $model->user_id = $user_id;
        $model->title = $title;
        $model->content = $content;
        $model->save();

        return json_result([
           'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect' => route('module.quiz'),
        ]);
    }
}
