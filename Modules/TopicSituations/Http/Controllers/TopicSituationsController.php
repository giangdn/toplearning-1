<?php

namespace Modules\TopicSituations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\TopicSituations\Entities\Topic;
use Modules\TopicSituations\Entities\Situation;
use Modules\TopicSituations\Entities\CommentSituation;
use App\Models\Categories\Area;
use App\Scopes\DraftScope;

class TopicSituationsController extends Controller
{
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('topicsituations::backend.topic.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        
        Topic::addGlobalScope(new DraftScope());
        $query = Topic::query();
        $query->select(['*']);
        $query->from('el_topic');

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
            $query->orWhere('code', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.topic_situations.edit', ['id' => $row->id]);
            $row->add_situation = route('module.create.situations', ['id' => $row->id]);
            $row->all_situation = route('module.situations', ['id' => $row->id]);
            $row->image = image_file($row->image);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'code' => 'required',
            'image' => 'required',
        ], $request, Topic::getAttributeName());

        $model = Topic::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        $sizes = config('image.sizes.news');
        $model->image = upload_image($sizes, $request->image);

        $model->isopen = $request->status;
        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function form(Request $request) {
        $model = Topic::select(['id','isopen','image','code','name'])->where('id', $request->id)->first();
        $path_image = image_file($model->image);
        json_result([
            'model' => $model,
            'image' => $path_image
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $key => $id) {
            $topic = Topic::find($id);
            $comment_situation = CommentSituation::where('topic_id',$id)->get();
            if (!$comment_situation->isEmpty()) {
                json_result([
                    'status' => 'error',
                    'message' => 'Không thể xóa xử lý tình huống: '. $topic->name .' vì đã có bình luận tình huống',
                ]); 
            } else {
                $topic->delete();
            }
        }
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function ajaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Cấp bậc',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = Topic::findOrFail($id);
                $model->isopen = $status;
                $model->save();
            }
        } else {
            $model = Topic::findOrFail($ids);
            $model->isopen = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save')
        ]);
    }

    // TÌNH HUỐNG
    public function situation($topic_id, Request $request) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $model = Topic::find($topic_id);
        return view('topicsituations::backend.situation.AllSituations',[
            'topic_id' => $topic_id,
            'model' => $model,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function createSituations($topic_id, Request $request) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('topicsituations::backend.situation.CreateSituations',[
            'topic_id' => $topic_id,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function saveSituations($topic_id, Request $request){
        $this->validateRequest([
            'name_situations' => 'required',
            'code_situations' => 'required',
            'description_situations' => 'required',
        ], $request, Situation::getAttributeName());
        
        $model = Situation::firstOrNew(['topic_id'=>$topic_id, 'code'=>$request->code_situations]);
        $model->code = $request->code_situations;
        $model->name = $request->name_situations;
        $model->description = html_entity_decode($request->description_situations);
        $model->topic_id = $topic_id;
        $model->save();

        if ($request->type == 1) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save')
            ]);
        } else {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.situations',['id'=>$topic_id]),
            ]);
        }
    }

    public function getSituation($topic_id, Request $request){
        $search = $request->input('search');
        $time_created = $request->input('time_created');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = Situation::query();
        $query->select(['a.*']);
        $query->from('el_situation as a');
        $query->where('a.topic_id', '=', $topic_id);

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('a.name', 'like', '%' . $search . '%');
                $subquery->orWhere('a.code', 'like', '%' . $search . '%');
                $subquery->orWhere('a.description', 'like', '%' . $search . '%');
            });
        }
        if ($time_created) {
            $query->Where('a.created_at', 'like', '%' . $search . '%');
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            $row->commentSituation = route('module.comment.situations', ['id' => $topic_id, 'situation' => $row->id]);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function ajaxEditSituations(Request $request){
        $id = $request->id;
        $situation = Situation::find($id);
        json_result($situation);
    }

    public function removeSituations($topic_id, Request $request){
        $ids = $request->input('ids');
        foreach ($ids as $key => $id) {
            $situation = Situation::find($id);
            $comment_situation = CommentSituation::where('situation_id',$id)->get();
            if (!$comment_situation->isEmpty()) {
                json_result([
                    'status' => 'error',
                    'message' => 'Không thể xóa thảo luận tình huống: '. $situation->name .' vì đã có bình luận tình huống',
                ]); 
            } else {
                $situation->delete();
            }
        }
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    // BÌNH LUẬN TÌNH HUỐNG
    public function commentSituations($topic_id, $situation_id, Request $request) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $model = Topic::find($topic_id);
        $situation = Situation::find($situation_id);
        return view('topicsituations::backend.comment_situation.index',[
            'topic_id' => $topic_id,
            'model' => $model,
            'situation' => $situation,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getCommentSituation($topic_id, $situation_id, Request $request){
        $search = $request->input('search');
        $unit = $request->input('unit');
        $title = $request->input('title');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = CommentSituation::query();
        $query->select([
            'cs.*',
            'p.firstname',
            'p.lastname',
            'u.name as unit_name',
            't.name as title_name',
            'u.manager.name as unit_manager',
        ]);
        $query->from('el_comment_situation as cs');
        $query->join('el_profile AS p', 'p.user_id', '=', 'cs.user_id');
        $query->leftJoin('el_unit AS u', 'u.id', '=', 'p.unit_id');
        $query->leftJoin('el_unit AS u_manager', 'u_manager.code', '=', 'u.parent_code');
        $query->leftJoin('el_titles AS t', 't.code', '=', 'p.title_code');
        $query->where('cs.topic_id', '=', $topic_id);
        $query->where('cs.situation_id', '=', $situation_id);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%'. $search .'%');
                $sub_query->orWhere('p.code', 'like', '%'. $search .'%');
            });
        }
        if ($title) {
            $query->where(function ($sub_query) use ($title) {
                $sub_query->orWhere('t.id', '=', $title);
            });
        }
        if ($request->area) {
            $query->leftJoin('el_area AS area', 'area.id', '=', 'u.area_id');
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->whereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if ($unit) {
            $query->where(function ($sub_query) use ($unit) {
                $sub_query->orWhere('u.id', '=', $unit);
            });
        }

        $count = $query->count();
        $query->orderBy('cs.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            $row->fullname = $row->lastname . ' ' . $row->firstname;
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
