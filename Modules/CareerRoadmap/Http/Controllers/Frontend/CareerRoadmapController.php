<?php

namespace Modules\CareerRoadmap\Http\Controllers\Frontend;

use App\Models\Categories\Subject;
use Illuminate\Support\Facades\Auth;
use Modules\CareerRoadmap\Entities\CareerRoadmapTitle;
use Modules\CareerRoadmap\Entities\CareerRoadmapTitleUser;
use Modules\CareerRoadmap\Entities\CareerRoadmapUser;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use Modules\CareerRoadmap\Entities\CareerRoadmap;
use App\Models\Categories\Titles;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Profile;

class CareerRoadmapController extends Controller
{
    public function index() {
        $profile = Profile::where('user_id', '=', \Auth::id())->first(['title_code']);
        $title = Titles::where('code', '=', $profile->title_code)->first(['id']);
        $roadmaps = CareerRoadmap::where('title_id', '=', @$title->id)
            ->get(['id', 'name']);

        $roadmaps_user = CareerRoadmapUser::query()
            ->where('user_id', '=', Auth::id())
            ->where('title_id', '=', @$title->id)
            ->get(['id', 'name']);

        $career_roadmaps = CareerRoadmap::query()
            ->where('title_id', '=', @$title->id)
            ->where('primary', '=', 1)
            ->latest()->first();

        if (url_mobile()){
            $sub_titles = $career_roadmaps ? $career_roadmaps->getTitles() : [];
            return view('themes.mobile.frontend.career_roadmap.index',[
                'sub_titles' => $sub_titles,
            ]);
        }

        return view('careerroadmap::frontend.index', [
            'roadmaps' => $roadmaps,
            'career_roadmaps' => $career_roadmaps,
            'roadmaps_user' => $roadmaps_user,
        ]);
    }

    public function getCourses($title_id, Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = TrainingRoadmap::query();
        $query->with('subject');
        $query->select([
            'a.id',
            'a.subject_id',
            'b.code AS subject_code',
            'b.name AS subject_name',
            'c.name AS title_name',
        ]);
        $query->from('el_trainingroadmap AS a');
        $query->leftJoin('el_subject AS b', 'b.id', '=', 'a.subject_id');
        $query->leftJoin('el_titles AS c', 'c.id', '=', 'a.title_id');
        $query->where('a.title_id', '=', $title_id);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $subject = Subject::find($row->subject_id);
            $row->result = $subject ? ($subject->isCompleted() ? 1 : 0) : 0;
			//$row->result = 0;
        }

        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function getChild(Request $request){
        $career_roadmap_title_id = $request->id;
        $type = $request->type;

        if ($type == 1){
            $childs = CareerRoadmapTitle::where('parent_id', '=', $career_roadmap_title_id)->get();
        }else{
            $childs = CareerRoadmapTitleUser::where('parent_id', '=', $career_roadmap_title_id)->get();
        }

        foreach ($childs as $item){
            $item->title_name = $item->title->name;
        }

        $data = ['childs' => $childs];
        return \response()->json($data);
    }

    public function save(Request $request) {
        $profile = Profile::find(Auth::id());
        $title = @$profile->titles;

        $this->validateRequest([
            'name' => 'required|max:500',
        ], $request, [
            'name' => trans('career.roadmap_name')
        ]);

        $primary = $request->post('primary');
        $model_id = $request->post('id');

        $model = CareerRoadmapUser::firstOrNew(['id' => $model_id]);
        $model->fill($request->all());
        $model->setAttribute('user_id', Auth::id());
        $model->setAttribute('title_id', @$title->id);

        if ($primary) {
            $model->setAttribute('primary', $primary);
        }

        $model->save();

        if (empty($model_id)) {
            $model->titles()->create([
                'title_id' => $title->id,
                'level' => 0
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => trans('backend.save_success'),
            // 'redirect' => route('module.career_roadmap.frontend'),
            'redirect' => route('module.frontend.user.my_career_roadmap'),
        ]);
    }

    public function addTitle(Request $request) {
        $roadmap = CareerRoadmapUser::findOrFail($request->post('id'));

        $this->validateRequest([
            'title_id' => 'required|exists:el_titles,id',
            'parent_title' => 'required',
            'seniority' => 'required',
        ], $request, [
            'title_id' => trans('app.title'),
            'parent_title' => trans('career.parent_title'),
            'seniority' => 'Thâm niên (năm)',
        ]);

        $title_id = $request->post('title_id');
        $parent_title = $request->post('parent_title');
        $parent = CareerRoadmapTitleUser::findOrFail($parent_title);

        $roadmap->titles()->create([
            'title_id' => $title_id,
            'parent_id' => $parent->id,
            'level' => $parent->level + 1,
            'seniority' => $request->seniority,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => trans('backend.save_success'),
            // 'redirect' => route('module.career_roadmap.frontend'),
            'redirect' => route('module.frontend.user.my_career_roadmap'),
        ]);
    }

    public function getParents(Request $request) {
        $titles_list = Titles::query()
            ->select(['id', 'code', 'name'])
            ->where('status', '=', 1)
            ->whereNotIn('id', function ($sub) use ($request){
                $sub->select(['title_id'])
                    ->from('career_roadmap_titles_user')
                    ->where('career_roadmap_user_id', '=', $request->roadmap_id)
                    ->pluck('title_id')
                    ->toArray();
            })
            ->get();

        $roadmap = CareerRoadmapUser::find($request->post('roadmap_id'));
        $career_roadmap_titles = $roadmap->titles()
            ->with('title')
            ->orderBy('level', 'ASC')
            ->get(['id', 'title_id', 'level']);

        $result = [];
        $parent_titles = [];
        foreach ($career_roadmap_titles as $title) {
            $parent_titles[] = [
                'id' => $title->id,
                'name' => str_repeat('-- ', $title->level) . $title->title->name,
            ];
        }

        $result['titles_list'] = $titles_list;
        $result['parent_titles'] = $parent_titles;

        return response()->json($result);
    }

    public function remove(Request $request) {
        $this->validateRequest([
            'roadmap_id' => 'required',
        ], $request, [
            'roadmap_id' => trans('career.roadmap_name')
        ]);

        CareerRoadmapTitleUser::where('id', '=', $request->post('roadmap_id'))->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function edit(Request $request) {
        $roadmap = CareerRoadmapTitleUser::query();
        $roadmap->select([
            'a.*',
            'b.name',
            'b.id AS titles_id '
        ]);
        $roadmap->from('career_roadmap_titles_user AS a')
            ->join('el_titles AS b', 'b.id', '=', 'a.title_id')
            ->where('a.id','=', $request->post('roadmap_id'));

        $rows = $roadmap->get();
        return response()->json($rows);
    }

    public function saveEditTitle(Request $request){
        $name = $request->post('title_id');
        $seniority = $request->post('seniority');
        $roadmap = CareerRoadmapTitleUser::find( $request->post('id'));
        $roadmap->update(['title_id'=>$name,'seniority'=>$seniority]);

        if($roadmap) {
            return response()->json([
                'status' => 'success',
                'message' => trans('backend.save_success'),
                // 'redirect' => route('module.career_roadmap.frontend'),
                'redirect' => route('module.frontend.user.my_career_roadmap'),
            ]);
        }
        else {
            return response()->json([
                'status' => 'unsuccessful',
                'message' => trans('backend.unsuccessful'),
                // 'redirect' => route('module.career_roadmap.frontend'),
                'redirect' => route('module.frontend.user.my_career_roadmap'),
            ]);
        }

    }

    public function removeRoadmap(Request $request) {
        $this->validateRequest([
            'roadmap_id' => 'required',
        ], $request, [
            'roadmap_id' => trans('career.roadmap_name')
        ]);

        CareerRoadmapTitleUser::where('career_roadmap_user_id', '=', $request->post('roadmap_id'))->delete();
        CareerRoadmapUser::where('id', '=', $request->post('roadmap_id'))->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
