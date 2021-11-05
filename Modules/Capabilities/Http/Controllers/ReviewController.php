<?php

namespace Modules\Capabilities\Http\Controllers;

use App\Permission;
use App\Models\Categories\UnitManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Capabilities\Entities\Capabilities;
use Modules\Capabilities\Entities\CapabilitiesCategory;
use Modules\Capabilities\Entities\CapabilitiesConventionPercent;
use Modules\Capabilities\Entities\CapabilitiesDictionary;
use Modules\Capabilities\Entities\CapabilitiesGroup;
use Modules\Capabilities\Entities\CapabilitiesResult;
use Modules\Capabilities\Entities\CapabilitiesReview;
use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use App\User;
use Modules\Capabilities\Entities\CapabilitiesReviewDetail;
use Modules\Capabilities\Entities\CapabilitiesTitle;
use Modules\Capabilities\Exports\ReviewExport;

class ReviewController extends Controller
{
    public function index() {
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        return view('capabilities::backend.capabilities_review.index', [
            'max_unit' => $max_unit,
            'level_name' => $level_name,
        ]);
    }

    public function detail($user_id) {
        $user = Profile::findOrFail($user_id);

        return view('capabilities::backend.capabilities_review.detail', [
            'user' => $user,
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $unit = $request->input('unit');
        $title = $request->input('title');
        $join_company = $request->input('join_company');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = Profile::query();
        $query->select([
            'a.id',
            'a.user_id',
            'a.code',
            'a.firstname',
            'a.lastname',
            'a.status',
            'a.join_company',
            'b.name AS unit_name',
            'c.name AS title_name',
            'd.name AS unit_manager',
        ])
            ->from('el_profile AS a')
            ->leftJoin('el_unit AS b', 'b.code', '=', 'a.unit_code')
            ->leftJoin('el_area AS area', 'area.id', '=', 'b.area_id')
            ->leftJoin('el_unit AS d', 'd.code', '=', 'b.parent_code')
            ->leftJoin('el_titles AS c', 'c.code', '=', 'a.title_code')
            ->where('a.user_id', '>', 2);

        if (!Permission::isAdmin()) {
            $profile = Profile::find(Auth::id());
            $unit_manager = UnitManager::where('user_code', '=', $profile->code)->pluck('unit_code')->toArray();
            $query->whereIn('b.code', $unit_manager);
//            $query->whereIn('b.id', UnitManager::getArrayUnitManagedByUser());
        }

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('a.code', 'like', '%'. $search .'%');
            });
        }

        if ($unit) {
            $unit = Unit::find($unit);
            $query->where('a.unit_code', '=', $unit->code);
        }

        if ($request->area) {
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->orWhereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }

        if ($title) {
            $title = Titles::find($title);
            $query->where('a.title_code', '=', $title->code);
        }

        if ($join_company){
            $query->where('a.join_company', '=', date_convert($join_company));
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $review = CapabilitiesResult::getLastReviewUser($row->user_id);

            $row->create_url = route('module.capabilities.review.user.create', ['user_id' => $row->user_id]);
            $row->review_url = route('module.capabilities.review.user.index', ['user_id' => $row->user_id]);
            $row->course_url = $review ? route('module.capabilities.review.user.view_course', ['user_id' => $row->user_id]) : '#';
            $row->join_company = get_date($row->join_company, 'd/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getDataDetail($user_id, Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = Profile::query();
        $query->select([
            'a.id',
            'a.name',
            'a.user_id',
            'b.firstname',
            'b.lastname',
            'a.created_at',
            'a.updated_at',
            'a.status'
        ])
            ->from('el_capabilities_review AS a')
            ->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.created_by')
            ->where('a.user_id', '=', $user_id);

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
            $row->edit_url = route('module.capabilities.review.user.edit', ['user_id' => $user_id, 'id' => $row->id]);
            $row->view_url = route('module.capabilities.review.user.view', ['user_id' => $user_id, 'id' => $row->id]);
            $row->export_url = route('module.capabilities.review.user.export', ['user_id' => $user_id, 'id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($user_id, $review_id = null) {
        $user = Profile::findOrFail($user_id);
        $unit = Unit::where('code', '=', $user->unit_code)->first();
        $title = Titles::where('code', '=', $user->title_code)->first();

        //$group = CapabilitiesGroup::getByTitle($user->title_id);
        $group = CapabilitiesGroup::getByTitle($title->id);
        $capabilities = function ($title_id, $category_id) {
            return Capabilities::getByTitleGroup($title_id, $category_id);
        };

        if (empty($review_id)) {
            $model = new CapabilitiesReview();
            return view('capabilities::backend.capabilities_review.form.create', [
                'model' => $model,
                'user' => $user,
                'group' => $group,
                'capabilities' => $capabilities,
                'unit' => $unit,
                'title' => $title,
            ]);
        }

        $model = CapabilitiesReview::findOrFail($review_id);
        $review_detail = function ($captitle_id, $review_id) {
            return CapabilitiesReviewDetail::getByCapabilitiesTitle($captitle_id, $review_id);
        };
        $convent = function ($percent) {
           return CapabilitiesConventionPercent::getConventPercent($percent);
        };
        return view('capabilities::backend.capabilities_review.form.create', [
            'model' => $model,
            'user' => $user,
            'group' => $group,
            'capabilities' => $capabilities,
            'unit' => $unit,
            'title' => $title,
            'review_detail' => $review_detail,
            'review_id' => $review_id,
            'convent' => $convent,
        ]);
    }

    public function view($user_id, $id) {
        $user = Profile::findOrFail($user_id);
        $unit = Unit::where('code', '=', $user->unit_code)->first();
        $title = Titles::where('code', '=', $user->title_code)->first();
        $model = CapabilitiesReview::findOrFail($id);
        $group = CapabilitiesReviewDetail::getGroup($id);
        $capabilities = function ($group_id, $review_id) {
            return CapabilitiesReviewDetail::getByGroup($group_id, $review_id);
        };
        $convent = function ($percent) {
            return CapabilitiesConventionPercent::getConventPercent($percent);
        };
        return view('capabilities::backend.capabilities_review.form.view', [
            'model' => $model,
            'user' => $user,
            'unit' => $unit,
            'title' => $title,
            'group' => $group,
            'capabilities' => $capabilities,
            'convent' => $convent,
        ]);
    }

    public function save($user_id, Request $request) {
        $this->validateRequest([
            'id' => 'nullable|exists:el_capabilities_review,id',
            'name' => 'required'
        ], $request, CapabilitiesReview::getAttributeName());

        $user = Profile::findOrFail($user_id);
        $title = Titles::where('code', '=', $user->title_code)->first();
        $convent = $request->convent_id;
        $comment = $request->comment;

        if (empty($request->id)) {
            $group = CapabilitiesGroup::getByTitle($title->id);
            $model = new CapabilitiesReview();
            $model->user_id = $user_id;
            $model->name = $request->name;
            $model->count_save = 1;
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
            $model->save();

            $sum_goal = 0 ;
            $sum_practical_goal = 0;

            foreach ($group as $item) {
                $caps = Capabilities::getByTitleGroup($title->id, $item->id);
                $total_weight_capa_cate = Capabilities::getTotalWeightByTitleGroup($title->id, $item->id);
                foreach ($caps as $cap) {
                    $practical_level = (int) $request->{'practical_level_' . $cap->id};
                    $detail = new CapabilitiesReviewDetail();
                    $detail->review_id = $model->id;
                    $detail->group_id = $item->id;
                    $detail->group_name = $item->name;
                    $detail->number = $cap->number_title;
                    $detail->captitle_id = $cap->id;
                    $detail->capabilities_id = $cap->capabilities_id;
                    $detail->capabilities_code = $cap->code;
                    $detail->capabilities_name = $cap->name;
                    $detail->standard_weight = $cap->weight;
                    $detail->standard_critical_level = $cap->critical_level;
                    $detail->standard_level = $cap->level;
                    $detail->standard_goal = $cap->goal;
                    $detail->practical_level = $practical_level;
                    $detail->practical_goal = CapabilitiesTitle::getGoal($practical_level, $cap->critical_level, $total_weight_capa_cate);
                    $detail->save();

                    $sum_goal += $detail->standard_goal;
                    $sum_practical_goal += $detail->practical_goal;
                }
            }

            $model->sum_goal = $sum_goal;
            $model->sum_practical_goal = $sum_practical_goal;
            $model->save();

            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.capabilities.review.user.edit', ['user_id' => $user_id, 'id' => $model->id]),
            ]);
        }

        $model = CapabilitiesReview::findOrFail($request->id);
        if ($model->count_save == 3) {
            json_message('Hết lượt sửa đánh giá', 'error');
        }
        $model->name = $request->name;
        $model->count_save += 1;
        $model->convent_id = json_encode($convent);
        $model->comment = $comment;
        $model->updated_by = Auth::id();
        $model->save();

        $sum_goal = 0 ;
        $sum_practical_goal = 0;

        $group = CapabilitiesGroup::getByTitle($title->id);

        foreach ($group as $item) {
            $caps = Capabilities::getByTitleGroup($title->id, $item->id);
            $total_weight_capa_cate = Capabilities::getTotalWeightByTitleGroup($title->id, $item->id);
            foreach ($caps as $cap) {
                $practical_level = (int) $request->{'practical_level_' . $cap->id};
                $review_detail = CapabilitiesReviewDetail::getByCapabilitiesTitle($cap->id, $request->id);
                if ($review_detail) {
                    $detail = CapabilitiesReviewDetail::find($review_detail->id);
                }
                else {
                    $detail = new CapabilitiesReviewDetail();
                }

                $detail->review_id = $model->id;
                $detail->group_id = $item->id;
                $detail->group_name = $item->name;
                $detail->number = $cap->number_title;
                $detail->captitle_id = $cap->id;
                $detail->capabilities_id = $cap->capabilities_id;
                $detail->capabilities_code = $cap->code;
                $detail->capabilities_name = $cap->name;
                $detail->standard_weight = $cap->weight;
                $detail->standard_critical_level = $cap->critical_level;
                $detail->standard_level = $cap->level;
                $detail->standard_goal = $cap->goal;
                $detail->practical_level = $practical_level;
                $detail->practical_goal = CapabilitiesTitle::getGoal($practical_level, $cap->critical_level, $total_weight_capa_cate);
                $detail->save();

                $sum_goal += $detail->standard_goal;
                $sum_practical_goal += $detail->practical_goal;
            }
        }

        $model->sum_goal = $sum_goal;
        $model->sum_practical_goal = $sum_practical_goal;
        $model->save();

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect' => route('module.capabilities.review.user.edit', ['user_id' => $user_id, 'id' => $model->id]),
        ]);
    }

    public function remove($user_id, Request $request) {
        $ids = $request->input('ids', null);
        $errors = [];
        foreach ($ids as $id) {
            $review = CapabilitiesReview::find($id);
            if (empty($review)) {
                continue;
            }

            if ($review->status == 1) {
                $errors[] = "Không thể xóa các đánh giá đã gửi";
                continue;
            }

            $review->delete();
        }

        if ($errors) {
            json_message(implode('<br>', $errors), 'error');
        }

        json_message('Đã xóa thành công');
    }

    public function getPracticalGoal($user_id, Request $request){
        $this->validateRequest([
            'captitleid' => 'required|exists:el_capabilities_title,id',
        ], $request, ['practical_level' => 'Cấp độ thực tế']);
        $sum_practical_level = 0;

        $practical_level = (float) $request->practical_level;
        $sum_practical_level += $practical_level;
        $captitle = CapabilitiesTitle::findOrFail($request->captitleid);

        $capabilites = Capabilities::find($captitle->capabilities_id);
        $total_weight_capa_cate = Capabilities::getTotalWeightByTitleGroup($captitle->title_id, $capabilites->category_id);

        $practical = CapabilitiesTitle::getGoal($practical_level, $captitle->critical_level, $total_weight_capa_cate);
        $foster = $practical >= $captitle->goal ? 'no' : 'yes';

        json_result([
            'practical' => $practical,
            'foster' => $foster,
            'sum_practical_level' => $sum_practical_level
        ]);
    }

    public function sendReview($user_id, Request $request) {
        $this->validateRequest([
            'ids' => 'required'
        ], $request);

        $ids = $request->ids;
        $errors = [];
        foreach ($ids as $id) {
            $review = CapabilitiesReview::find($id);
            if (empty($review)) {
                continue;
            }
            if ($review->count_send == 3){
                $errors[] = 'Hết lượt gửi đánh giá';
                continue;
            }

            $review->count_send += 1;
            $review->status = 1;
            $review->updated_by = Auth::id();
            $review->save();
        }

        if($errors) {
            json_message(implode('<br>', $errors), 'error');
        }
        json_message('Đã gửi thành công');
    }

    public function exportReview($user_id, $review_id)
    {
        $export = new ReviewExport($user_id, $review_id);
        return ($export)->download('danh_sach_danh_gia_khung_nang_luc_'. date('d_m_Y') .'.xlsx');
    }

    public function modalDictionary($user_id, $capabilities_id){
        $capabilities = Capabilities::find($capabilities_id);
        $dictionary = CapabilitiesDictionary::where('capabilities_id', '=', $capabilities->id)->first();

        return view('capabilities::backend.modal.dictionary', [
            'dictionary' => $dictionary,
            'capabilities' => $capabilities,
        ]);
    }

    public function viewCourse($user_id) {
        $user = Profile::findOrFail($user_id);
        $unit = Unit::where('code', '=', $user->unit_code)->first();
        $title = Titles::where('code', '=', $user->title_code)->first();

        if (url_mobile()){
            return view('themes.mobile.frontend.capabilities.course', [
                'user' => $user,
                'unit' => $unit,
                'title' => $title,
            ]);
        }

        return view('capabilities::backend.capabilities_review.course', [
            'user' => $user,
            'unit' => $unit,
            'title' => $title,
        ]);
    }

    public function chartCourse($user_id, Request $request){

        $data = [];

        $data[] = [
            'Tháng',
            'Đánh giá hiện tại',
            'Đánh giá cũ',
        ];

        for ($i = 1; $i <= 12; $i++){
            $course_now = CapabilitiesResult::getCourseNowByMonth($user_id, $i);
            $course_old = CapabilitiesResult::getCourseOldByMonth($user_id, $i);
            $data[] = [
                ($i%2 != 0) ? 'T'.$i : '',
                $course_now,
                $course_old
            ];
        }

        return \response()->json($data);
    }
}
