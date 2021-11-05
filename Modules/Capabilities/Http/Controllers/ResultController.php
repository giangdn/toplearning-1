<?php

namespace Modules\Capabilities\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Capabilities\Entities\Capabilities;
use Modules\Capabilities\Entities\CapabilitiesResult;
use Modules\Capabilities\Entities\CapabilitiesResultDetail;
use Modules\Capabilities\Entities\CapabilitiesReview;

class ResultController extends Controller
{
    public function index()
    {

        return view('capabilities::backend.capabilities_result.index', [

        ]);
    }

    public function form($id = null) {
        $users = CapabilitiesResult::getAllUserReviewed();
        $subject_missing = function ($user_id, $start_date=null, $end_date=null){
            return CapabilitiesResult::getSubjectMissingUser($user_id, $start_date, $end_date);
        };

        $detail = function ($subject_id, $result_id, $user_id){
          return CapabilitiesResultDetail::checkExists($subject_id, $result_id, $user_id);
        };

        if (empty($id)) {
            $model = new CapabilitiesResult();
            return view('capabilities::backend.capabilities_result.form', [
                'users' => $users,
                'subject_missing' => $subject_missing,
                'model' => $model,
                'detail' => $detail
            ]);
        }

        $model = CapabilitiesResult::findOrFail($id);
        return view('capabilities::backend.capabilities_result.form', [
            'users' => $users,
            'subject_missing' => $subject_missing,
            'model' => $model,
            'detail' => $detail
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'id' => 'nullable|exists:el_capabilities_result,id',
            'name' => 'required'
        ], $request);

        $id = $request->id;
        $users = CapabilitiesResult::getAllUserReviewed();
        if ($users) {
            if (empty($id)) {
                $model = new CapabilitiesResult();
                $model->created_by = Auth::id();
            }else {
                $model = CapabilitiesResult::find($id);
            }

            $model->name = $request->name;
            $model->updated_by = Auth::id();
            $model->save();
        }

        foreach($users as $user) {
            $subjects = CapabilitiesResult::getSubjectMissingUser($user->user_id);
            $count = $subjects->count();
            if($count <= 0) {
                continue;
            }

            $review_id = 0;
            foreach($subjects as $subject) {
                $exists = CapabilitiesResultDetail::checkExists($subject->subject_id, $model->id, $user->user_id);
                if ($subject->review_id) {
                    $review_id = $subject->review_id;
                }

                if ($exists) {
                    $detail = CapabilitiesResultDetail::find($exists->id);
                }
                else {
                    $detail = new CapabilitiesResultDetail();
                    $detail->result_id = $model->id;
                    $detail->subject_id = $subject->subject_id;
                    $detail->user_id = $user->user_id;
                }

                $detail->subject_code = $subject->subject_code;
                $detail->subject_name = $subject->subject_name;
                $detail->capabilities_id = $subject->capabilities_id;
                $detail->capabilities_code = $subject->capabilities_code;
                $detail->capabilities_name = $subject->capabilities_name;
                $detail->priority_level = $request->{'priority_level_'. $user->user_id .'_'. $subject->id .'_'. $subject->capabilities_id . '_' . $subject->subject_id};
                $detail->training_time = $request->{'training_time_'. $user->user_id .'_'. $subject->id .'_'. $subject->capabilities_id . '_' . $subject->subject_id};
                $detail->training_form = $request->{'training_form_'. $user->user_id .'_'. $subject->id .'_'. $subject->capabilities_id . '_' .$subject->subject_id};
                $detail->save();
            }

            if ($review_id) {
                $review = CapabilitiesReview::find($review_id);
                if (empty($review)) {
                    continue;
                }

                if ($review->status != 1) {
                    $review->status = 1;
                    $review->updated_by = Auth::id();
                    $review->save();
                }
            }
        }

        json_result([
            'status' => 'success',
            'message' => 'Lưu kế hoạch thành công',
            'redirect' => route('module.capabilities.review.result.index')
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = CapabilitiesResult::query();
        $query->select([
            'a.*',
            'b.firstname',
            'b.lastname',
        ])
            ->from('el_capabilities_result AS a')
            ->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.created_by');

        if (!Permission::isAdmin()){
            $query->where('a.created_by', '=', Auth::id());
        }

        if ($search) {
            $query->where('a.name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->created_date = get_date($row->created_at, 'H:i d/m/Y');
            $row->updated_date = get_date($row->updated_at, 'H:i d/m/Y');
            $row->edit_url = route('module.capabilities.review.result.edit', ['id' => $row->id]);
            $row->export_url = route('module.capabilities.review.result.export', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function send(Request $request) {
        $this->validateRequest([
            'ids' => 'required'
        ], $request);

        $ids = $request->ids;
        foreach ($ids as $id) {
            $item = CapabilitiesResult::find($id);
            if ($item->status != 1) {
                $item->status = 1;
                $item->save();
            }
        }

        json_message('Gửi các kế hoạch thành công');
    }

    public function remove(Request $request) {
        $this->validateRequest([
            'ids' => 'required'
        ], $request);

        $ids = $request->ids;
        $errors = [];
        foreach ($ids as $id) {
            $item = CapabilitiesResult::find($id);
            if ($item->status != 1) {
                $item->delete();
            }
            else {
                $errors[] = 'Kế hoạch <b>'. $item->name .'</b> đã gửi không thể xóa';
            }
        }

        if ($errors) {
            json_message(implode('<br>', $errors), 'error');
        }

        json_message('Xóa các kế hoạch thành công');
    }
}
