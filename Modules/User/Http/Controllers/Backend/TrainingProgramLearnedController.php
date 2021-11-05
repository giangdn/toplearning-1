<?php

namespace Modules\User\Http\Controllers\Backend;

use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\User\Entities\TrainingProgramLearned;

class TrainingProgramLearnedController extends Controller
{
    public function index($user_id) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $full_name = Profile::fullname($user_id);
        return view('user::backend.training_program_learned.index', [
            'user_id' => $user_id,
            'full_name' => $full_name,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData($user_id, Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = TrainingProgramLearned::query();
        $query->select([
            'a.*',
            'b.email'
        ]);
        $query->leftJoin('el_profile AS b','b.user_id', '=', 'a.user_id');
        $query->from('el_training_program_learned as a');
        $query->where('a.user_id', '=', $user_id);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->code = Profile::usercode($user_id);
            $row->fullname = Profile::fullname($user_id);
            $row->edit_url = route('module.backend.training_program_learned.edit', ['user_id' => $user_id, 'id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($user_id, $id = 0, Request $request) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $full_name = Profile::fullname($user_id);

        if ($id){
            $model = TrainingProgramLearned::find($id);
            return view('user::backend.training_program_learned.form', [
                'model' => $model,
                'user_id' => $user_id,
                'full_name' => $full_name,
                'get_menu_child' => $get_menu_child,
                'name_url' => $get_name_url[4],
            ]);
        }
        else{
            $model = new TrainingProgramLearned();
            return view('user::backend.training_program_learned.form', [
                'model' => $model,
                'user_id' => $user_id,
                'full_name' => $full_name,
                'get_menu_child' => $get_menu_child,
                'name_url' => $get_name_url[4],
            ]);
        }
    }

    public function save($user_id, Request $request) {
        $model = TrainingProgramLearned::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->user_id = $user_id;
        $model->save();

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect' => route('module.backend.training_program_learned', ['user_id' => $user_id])
        ]);
    }

    public function remove($user_id, Request $request) {
        $ids = $request->input('ids', null);
        TrainingProgramLearned::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
