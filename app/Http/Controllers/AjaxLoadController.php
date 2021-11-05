<?php

namespace App\Http\Controllers;

use App\Models\Categories\Area;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\Position;
use App\Models\Categories\TitleRank;
use App\PermissionType;
use App\Profile;
use App\Models\Categories\Subject;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingProgram;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\Unit;
use App\ProfileStatus;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizType;
use App\Models\Categories\TrainingObject;
use App\Models\Categories\TrainingPartner;
use App\Models\Categories\TeacherType;
use App\Models\Categories\TrainingType;
use Modules\TableManager\Entities\Table;
use Modules\News\Entities\NewsCategory;
use Modules\NewsOutside\Entities\NewsOutsideCategory;

class AjaxLoadController extends Controller
{
    public function loadAjax($func, Request  $request) {
        if (method_exists($this, $func) && Auth::check()) {
            $this->{$func}($request);
            exit();
        }
        json_message('Yêu cầu không hợp lệ', 'error');
    }

    private function loadUnitByLevel(Request $request) {
        $level = $request->level;
        $parent_id = $request->parent_id;
        $search = $request->search;

        Unit::addGlobalScope(new DraftScope());
        $query = Unit::query();
        $query->select(\DB::raw('id, CONCAT(code, \' - \', name) AS text'));
        $query->where('status', '=', 1);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        if ($level!='') {
            $query->where('level', '=', $level);
        }

        if ($parent_id) {
            $parent_code = Unit::where(['id' => $parent_id])
                ->firstOr(['code'])->code;
            $query->where('parent_code', '=', $parent_code);
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadAreaByLevel(Request $request) {
        $level = $request->level;
        $parent_id = $request->parent_id;
        $search = $request->search;

        $query = Area::query();
        $query->select(\DB::raw('id, CONCAT(code, \' - \', name) AS text'));
        $query->where('status', '=', 1);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        if ($level) {
            $query->where('level', '=', $level);
        }

        if ($parent_id) {
            $parent_code = Area::where(['id' => $parent_id])
                ->firstOr(['code'])->code;
            $query->where('parent_code', '=', $parent_code);
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadTitle(Request $request) {
        $search = $request->search;
        $position_id = $request->position_id;

        Titles::addGlobalScope(new DraftScope());
        $query = Titles::query();
        $query->where('status', '=', 1);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }
        if ($position_id){
            $query->where('position_id', '=', $position_id);
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get(['id', 'name AS text']);
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }
    private function loadTitleRank(Request $request) {
        $search = $request->search;

        TitleRank::addGlobalScope(new DraftScope());
        $query = TitleRank::query();
        $query->where('status', '=', 1);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get(['id', 'name AS text']);
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }
    private function loadTrainingProgram(Request $request) {
        $search = $request->search;

        TrainingProgram::addGlobalScope(new DraftScope());
        $query = TrainingProgram::query();
        $query->where('status', '=', 1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('code', 'like', '%' . $search . '%');
                $sub_query->orWhere('name', 'like', '%' . $search . '%');
            });
        }

        $query->orderBy('id', 'desc');
        $paginate = $query->paginate(10);
        $data['results'] = $query->get(\DB::raw('id, CONCAT(code, \' - \', name) AS text'));
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

	private function loadTrainingObject(Request $request) {
        $search = $request->search;
        $query = TrainingObject::query();
        $query->where('status', '=', 1);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $query->orderBy('id', 'desc');
        $paginate = $query->paginate(10);
        $data['results'] = $query->get(['id', 'name AS text']);
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

	private function loadTrainingPartner(Request $request) {
        $search = $request->search;
        $query = TrainingPartner::query();
       // $query->where('status', '=', 1);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $query->orderBy('id', 'desc');
        $paginate = $query->paginate(10);
        $data['results'] = $query->get(['id', 'name AS text']);
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadLevelSubject(Request $request) {
        $search = $request->search;
        $training_program = (int) $request->training_program;

        $query = LevelSubject::query();
        $query->where('status', '=', 1);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        if ($training_program) {
            $query->where('training_program_id', '=', $training_program);
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get(['id', 'name AS text']);
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadSubject(Request $request) {
        $search = $request->search;
        $training_program = (int) $request->training_program;
        $level_subject_id = (int) $request->level_subject_id;
        $course_type = $request->course_type;

        Subject::addGlobalScope(new DraftScope());
        $query = Subject::query();
        $query->where('status', '=', 1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('code', 'like', '%' . $search . '%');
                $sub_query->orWhere('name', 'like', '%' . $search . '%');
            });
        }

        if ($training_program) {
            $query->where('training_program_id', '=', $training_program);
        }

        if ($level_subject_id){
            $query->where('level_subject_id', '=', $level_subject_id);
        }

        if ($course_type){
            $query->whereIn('id', function ($sub) use ($course_type){
                $sub->select(['subject_id'])
                    ->from('el_course_view')
                    ->whereIn('course_type', $course_type)
                    ->pluck('subject_id')
                    ->toArray();
            });
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get(\DB::raw('id, CONCAT(code, \' - \', name) AS text'));
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadUser(Request $request) {
        $search = $request->search;

        Profile::addGlobalScope(new DraftScope('user_id'));
        $query = Profile::query()
            ->where('status', '=', 1)
            ->where('type_user', '=', 1)
            ->where('user_id', '>', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
            });
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get(\DB::raw('user_id AS id, CONCAT(code, \' - \', lastname, \' \', firstname) AS text'));
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadAllUser(Request $request) {
        $search = $request->search;

        Profile::addGlobalScope(new DraftScope('user_id'));
        $query = Profile::query()
            ->where('type_user', '=', 1)
            ->where('user_id', '>', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
            });
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get(\DB::raw('user_id AS id, CONCAT(code, \' - \', lastname, \' \', firstname) AS text'));
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadTeacher(Request $request) {
        $search = $request->search;
        $query = TrainingTeacher::query()
            ->where('status', '=', 1)
            ->where('user_id', '>', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
                $sub_query->orWhere('name', 'like', '%' . $search . '%');
            });
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get(\DB::raw('id, name AS text'));
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

	private function loadTeacherType(Request $request) {
        $search = $request->search;
        $query = TeacherType::query()
            ->where('status', '=', 1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
                $sub_query->orWhere('name', 'like', '%' . $search . '%');
            });
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get(\DB::raw('id, name AS text'));
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

	private function loadTrainingType(Request $request) {
        $search = $request->search;
        $query = TrainingType::query()
            ->where('status', '=', 1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
                $sub_query->orWhere('name', 'like', '%' . $search . '%');
            });
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get(\DB::raw('id, name AS text'));
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadQuizCourse(Request $request) {
        $course_id = $request->course_id;
        $search = $request->search;

        $query = Quiz::query();
        $query->where('status', '=', 1)
            ->where('quiz_type', '=', 2)
            ->where(function($where) use ($course_id){
                $where->orWhereNull('course_id');
                $where->orWhere('course_id', '=', 0);
                $where->orWhere('course_id', '=', $course_id);
            });

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);

        $data['results'] = $query->get(['id', 'name AS text']);

        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        json_result($data);
    }

    private function loadQuizCourseOnline(Request $request) {
        $course_id = $request->course_id;
        $search = $request->search;

        $query = Quiz::query();
        $query->where('status', '=', 1)
            ->where('quiz_type', '=', 1)
            ->where('course_id', '=', $course_id);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get(['id', 'name AS text']);
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        json_result($data);
    }

    private function loadPartQuizCourseOnline(Request $request) {
        $quiz_id = $request->quiz_id;
        $search = $request->search;

        $query = QuizPart::where('quiz_id', '=', $quiz_id);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get(['id', 'name AS text']);
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        json_result($data);
    }

    private function loadTrainingForm(Request $request) {
        $search = $request->search;

        TrainingForm::addGlobalScope(new DraftScope());
        $query = TrainingForm::query();

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get(['id', 'name AS text']);
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadQuizType(Request $request) {
        $search = $request->search;

        QuizType::addGlobalScope(new CompanyScope());
        $query = QuizType::query();

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get(['id', 'name AS text']);
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadPosition(Request $request) {
        $search = $request->search;

        $query = Position::query();
        $query->where('status', '=', 1);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get(['id', 'name AS text']);
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadCategoryNew(Request $request) {
        $search = $request->search;

        $query = NewsCategory::query();
        $query->whereNull('parent_id');

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get(['id', 'name AS text']);
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadCategoryNewOutside(Request $request) {
        $search = $request->search;

        $query = NewsOutsideCategory::query();
        $query->whereNull('parent_id');

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get(['id', 'name AS text']);
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }

    private function loadStatusProfile(Request $request){
        $search = $request->search;

        $query = ProfileStatus::query();

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get(['id', 'name AS text']);
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }
    private function loadGroupPermission(Request $request){
        $search = $request->search;

        $query = PermissionType::where('type', '=', 2);
        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get(['id', 'name AS text']);
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }
    private function loadTable(Request $request){
        $search = $request->search;

        $query =  Table::query();
        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get(['id', 'name AS text']);
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        json_result($data);
    }
}
