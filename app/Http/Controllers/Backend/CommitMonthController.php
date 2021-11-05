<?php

namespace App\Http\Controllers\Backend;

use App\Models\Categories\CommitGroup;
use App\Models\Categories\CommitMentTitle;
use App\Models\Categories\CommitMonth;
use App\Models\Categories\TitleRank;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingType;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class CommitMonthController extends Controller
{
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('backend.category.commitment.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        CommitGroup::addGlobalScope(new DraftScope());
        $query = CommitGroup::select('*');
        $count = $query->count();
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $row->min_cost = numberFormat($row->min_cost);
            $row->max_cost = numberFormat($row->max_cost);
            $row->titles = $this->getTitles($row->id);
            $row->edit = route('backend.category.commit_month.edit', ['id' => $row->id]);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function showModalFrameCommit(Request $request)
    {
        $group_id = $request->input('commit_group');

        return view('backend.category.commitment.modal_commit', [
            'group_id' => $group_id,
        ]);
    }
    public function getDataFrame(Request $request, $commit_group_id)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        CommitMonth::addGlobalScope(new DraftScope());
        $query = CommitMonth::select('*');
        $count = $query->count();
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->min_cost = numberFormat($row->min_cost);
            $row->max_cost = numberFormat($row->max_cost);
            $row->edit = route('backend.category.commit_month.edit', ['id' => $row->id]);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function deleteCommitFrame(Request $request)
    {
        $id = $request->id;
        CommitMonth::destroy($id);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
    public function getCommitFrame($id)
    {
        $data =  CommitMonth::find($id);
        json_result(['status'=>'success','result'=>$data]);
    }
    private function getTitles($id){
        if(!$id)
            return '';
        $titles = TitleRank::join('el_commitment_title as b','el_title_rank.id','=','b.title_id')->where('commit_group_id',$id)->get();
        $str =[];
        foreach ($titles as $index => $title) {
            $str[] = $title->name;
        }
        return count($str)>0 ?implode('<br>',$str):'';
    }
    public function save(Request $request) {
        $this->validateRequest([
            'min_cost' => 'required',
            'max_cost' => 'required',
            'month' => 'required',
//            'titles' => ['required',function($attribute, $value, $fail) use ($request){
//                foreach ($value as $index => $item) {
//                    $query =CommitMentTitle::where('title_id',$item);
//                    $query->where('min_cost','<=',$request->min_cost);
//                    $query->where('max_cost','>=',$request->max_cost);
//                    if ($request->training_type_id)
//                        $query->where('training_type_id','=',$request->training_type_id);
//                    if ($request->id)
//                        $query->where('commitment_id','<>',$request->id);
//                    $query = $query->exists();
//                    if ($query)
//                        return $fail(CommitMonth::getAttributeName()[$attribute].' đã tồn tại.');
//                }
//            }]
        ], $request, CommitMonth::getAttributeName());

        $find = [',', ';', '.'];

        $min_cost = str_replace($find, '', $request->min_cost);
        $max_cost = str_replace($find, '', $request->max_cost);
        $check1 = CommitMonth::query()
            ->where('id', '!=', $request->id)
            ->where('min_cost', '<=', $min_cost)
            ->where('max_cost', '>=', $min_cost);

        if ($check1->exists()) {
            json_result([
                'status' => 'error',
                'message' => 'Chi phí nhập đã tồn tại',
            ]);
        }

        if ($max_cost){
            $check2 = CommitMonth::query()
                ->where('id', '!=', $request->id)
                ->where('min_cost', '<=', $max_cost)
                ->where('max_cost', '>=', $max_cost);
            if ($check2->exists()) {
                json_result([
                    'status' => 'error',
                    'message' => 'Chi phí nhập đã tồn tại',
                ]);
            }
            if ($min_cost >= $max_cost){
                json_result([
                    'status' => 'error',
                    'message' => 'Khoảng chi phí không hợp lệ',
                ]);
            }
        }

        $model = CommitMonth::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
            ]);
        }
        json_message('Không thể lưu', 'error');
    }

    public function saveGroup(Request $request)
    {
        $this->validateRequest([
            'group' => 'required',
        ], $request, CommitMonth::getAttributeName());
        $titles = $request->input('titles');
        $model = CommitGroup::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
//        $model->titles = json_encode($titles);
        if ($model->save()) {
            //save commit title
            CommitMentTitle::where('commit_group_id',$model->id)->delete();
            foreach ($titles as $index => $title) {
                CommitMentTitle::updateOrCreate(['commit_group_id'=>$model->id,'title_id'=>$title]);
            }
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
            ]);
        }
        json_message('Không thể lưu', 'error');
    }

    public function form(Request $request) {
        $model = CommitGroup::findOrFail($request->id);
        $titles = TitleRank::join('el_commitment_title as b','el_title_rank.id','=','b.title_id')->where('commit_group_id',$model->id)->get();
        $training_type = $model->training_type_id ?TrainingType::where(['id'=>$model->training_type_id])->get():[];

        // dd($unit_managers);
        json_result([
            'model' => $model,
            'titles' => $titles,
            'training_type' => $training_type,
        ]);
    }

    public function form1($id = 0) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        if ($id) {
            $model = CommitGroup::find($id);
            $page_title = trans('backend.edit');
            
            return view('backend.category.commitment.form', [
                'model' => $model,
                'page_title' => $page_title,
                'titles' => $titles,
                'training_type' => $training_type,
                'get_menu_child' => $get_menu_child,
                'name_url' => $get_name_url[4],
            ]);
        }
        $model =  new CommitGroup();
        $page_title = trans('backend.add_new');

        return view('backend.category.commitment.form', [
            'model' => $model,
            'page_title' => $page_title,
            'titles'=>[],
            'training_type'=>[],
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);

        CommitMonth::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
