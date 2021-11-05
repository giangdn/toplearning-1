<?php

namespace Modules\Quiz\Http\Controllers\Backend;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizSettingAlert;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Quiz\Imports\UserSecondaryImport;
use Modules\User\Entities\LoginFail;

class QuizUserSecondaryController extends Controller
{
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $errors = session()->get('errors');
        \Session::forget('errors');

        return view('quiz::backend.user_secondary.index', [
            'errors' => $errors,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function form(Request $request) {
        $model = QuizUserSecondary::select(['id','username','dob','email','code','name','identity_card'])->where('id', $request->id)->first();
        $username = str_replace('secondary_', '', $model->username);
        $dob = get_date($model->dob);
        json_result([
            'model' => $model,
            'username' => $username,
            'dob' => $dob,
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        QuizUserSecondary::addGlobalScope(new DraftScope());
        $query = QuizUserSecondary::query();
        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('name', 'like', '%'. $search .'%');
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->username = str_replace('secondary_', '', $row->username);
            $row->edit_url = route('module.quiz.user_secondary.edit', ['id' => $row->id]);
            $row->dob = get_date($row->dob, 'd/m/Y');
            $row->created_at2 = get_date($row->created_at, 'd/m/Y');

            $login_fail_user_second = LoginFail::query()
                ->where('user_id', '=', $row->id)
                ->where('username', '=', $row->username)
                ->where('user_type', '=', 2)
                ->first();

            $row->status_clock = ($login_fail_user_second && $login_fail_user_second->num_fail == 3) ? 1 : 0;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_quiz_user_secondary,code,'. $request->id,
            'name' => 'required',
            'username' => 'required_if:id,==,|min:6|max:32',
            'password' => 'nullable|min:8|max:32|required_if:id,==,',
            'repassword' => 'same:password',
            'email' => 'nullable|email',
            'identity_card' => 'required|min:9|max:14',
        ], $request, QuizUserSecondary::getAttributeName());

        if (empty($request->id)){
            $setting_alert = QuizSettingAlert::query()->first();

            if ($setting_alert){
                $user_second = QuizUserSecondary::query()
                    ->where('identity_card', '=', $request->identity_card)
                    ->whereRaw(dateAddSql('created_at', $setting_alert->from_time, 'day') ." <= '". now() ."'")
                    ->whereRaw(dateAddSql('created_at', $setting_alert->to_time, 'day') ." >= '". now() ."'")
                    ->first();

                if ($user_second){
                    session()->put('errors', ['CMND '. $user_second->identity_card .' đã được thêm trước đó']);
                    session()->save();
                }
            }
        }

        $model = QuizUserSecondary::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->username = 'secondary_'. $model->username;
        if ($model->dob) {
            $model->dob = date_convert($model->dob);
        }

        $model->password = password_hash($request->input('password'), PASSWORD_DEFAULT);

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);

        foreach ($ids as $id){
            $second = QuizUserSecondary::find($id);

            $register = QuizRegister::query()
                ->where('user_id', '=', $second->id)
                ->where('type', '=', 2);

            if ($register->exists()){
                continue;
            }else{
                $second->delete();
            }
        }

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function importUserSecondary(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new UserSecondaryImport();
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('module.quiz.user_secondary'),
        ]);
    }

    public function lockUserSecond(Request $request) {
        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        // dd($status);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $user_second = QuizUserSecondary::find($id);
                if ($status == 1){
                    LoginFail::query()
                        ->updateOrCreate([
                            'user_id' => $id,
                            'user_type' => 2,
                            'username' => str_replace('secondary_', '', $user_second->username)
                        ], [
                            'num_fail' => 3
                        ]);
                }else{
                    LoginFail::query()
                        ->updateOrCreate([
                            'user_id' => $id,
                            'user_type' => 2,
                            'username' => str_replace('secondary_', '', $user_second->username)
                        ], [
                            'num_fail' => 0
                        ]);
                }
            }
        } else {
            $user_second = QuizUserSecondary::find($ids);
            if ($status == 1){
                LoginFail::query()
                    ->updateOrCreate([
                        'user_id' => $ids,
                        'user_type' => 2,
                        'username' => str_replace('secondary_', '', $user_second->username)
                    ], [
                        'num_fail' => 3
                    ]);
            }else{
                LoginFail::query()
                    ->updateOrCreate([
                        'user_id' => $ids,
                        'user_type' => 2,
                        'username' => str_replace('secondary_', '', $user_second->username)
                    ], [
                        'num_fail' => 0
                    ]);
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save')
        ]);
    }
}
