<?php

namespace App\Http\Controllers\Backend;

use App\Jobs\NotifyUserOfCompletedImportUser;
use App\Exports\LanguagesExport;
use App\Imports\ImportLanguages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Languages;
use App\LanguagesGroups;

class LanguagesController extends Controller
{
    public function index($id = null) {

        if(!$id) {
            $lg = LanguagesGroups::first(["id"]);
            return redirect('admin-cp/languages/'.$lg->id);
        }

        $groups = LanguagesGroups::all()->toArray();

        return view('backend.languages.index',[
            'id' => $id,
            'groups' => $groups,
        ]);
    }

    public function form($idg, $id = null) {
        $model = Languages::firstOrNew(['id' => $id]);
        $page_title = $model->id ? $model->pkey : 'Thêm mới';

        $groups_name = LanguagesGroups::find($idg)->toArray();
        return view('backend.languages.form', [
            'model' => $model,
            'id' => $idg,
            'groups_name' => $groups_name["name"],
            'page_title' => $page_title,
        ]);

    }

    public function synchronize() {

        $dir = app()['path.lang'];
        $groups = LanguagesGroups::all()->toArray();

        foreach ($groups as $v){
            $model = Languages::where('groups_id','=',$v["id"])->get()->toArray();
            $slug = $v["slug"];
            $dir_vn = $dir ."/vi/la".$slug.".php";
            $dir_en = $dir ."/en/la".$slug.".php";

            $content = "<?php \n
        return[ \n";
            $content_en = "<?php \n
        return[ \n";
            foreach($model as $k=>$v){

                $pkey = preg_replace('/\s+/', ' ',$v["pkey"]);

                if(isset($v["content"]) && $v["content"])
                    $content .= "'".$pkey."' => '".str_replace("'", "\'", $v["content"])."', \n";

                if(isset($v["content_en"]) && $v["content_en"])
                    $content_en .= "'".$pkey."' => '".str_replace("'", "\'", $v["content_en"])."', \n";

            }
            $content .= "];\n
        ?>";
            $content_en .= "];\n
        ?>";
            file_put_contents($dir_vn, $content);
            file_put_contents($dir_en, $content_en);
        }


        return redirect()->route('backend.languages');
    }

    public function getData($idg, Request $request) {
        $search = $request->input('search');
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = Languages::query();
        $query->select([
            'a.*',
            'b.name AS group_name',
        ]);
        $query->from('el_languages AS a');
        $query->leftJoin('el_languages_groups AS b', 'b.id', '=', 'a.groups_id');

        if ($search) {
            $query->where(function ($query)use ($search) {
                $query->orWhere('a.pkey', 'like', '%'.$search.'%');
                $query->orWhere('a.content', 'like', '%'.$search.'%');
                $query->orWhere('a.content_en', 'like', '%'.$search.'%');
            });
        }
        else {
            $query->where('a.groups_id','=',$idg);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->edit_url = route('backend.languages.edit', ['idg' => $idg, 'id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->post('ids', []);
        Languages::destroy($ids);
        json_message('Đã xóa thành công');
    }

    public function save($idg, Request $request) {
        $this->validateRequest([
            'pkey' => 'required',
            'content' => 'required',

        ], $request, Languages::getAttributeName());

        $model = Languages::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->groups_id = $idg;
        if ($model->save()) {

            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('backend.languages.group', $idg)
            ]);
        }

        json_message('Không thể lưu', 'error');

    }

    public function saveGroup(Request $request) {
        $this->validateRequest([
            'name' => 'required',
        ], $request, LanguagesGroups::getAttributeName());

        $model = LanguagesGroups::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->slug = \Illuminate\Support\Str::slug($model->name);

        if ($model->save()) {

            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('backend.languages.group', $model->id)
            ]);
        }

        json_message('Không thể lưu', 'error');

    }

    public function export_file() {
        $model = Languages::all()->toArray();

        $content = "<?php \n
        return[ \n";
        $content_en = "<?php \n
        return[ \n";
        foreach($model as $k=>$v){

            $pkey = preg_replace('/\s+/', ' ',$v["pkey"]);

            if(isset($v["content"]) && $v["content"])
            $content .= "'".$pkey."' => '".str_replace("'", "\'", $v["content"])."', \n";

            if(isset($v["content_en"]) && $v["content_en"])
            $content_en .= "'".$pkey."' => '".str_replace("'", "\'", $v["content_en"])."', \n";

        }
        $content .= "];\n
        ?>";
        $content_en .= "];\n
        ?>";
        return response()->attachment('content.txt', $content . $content_en);

    }

    public function import_languages(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, [
            'import_file' => 'File import'
        ]);

        $file = $request->file('import_file');
        $name = 'import_user_' . Str::random(10) . '.' . $file->extension();
        $newfile = $file->move(storage_path('import_files'), $name);

        if($newfile) {
            (new ImportLanguages(\Auth::user()))->queue($newfile)->chain([
                new NotifyUserOfCompletedImportUser(\Auth::user()),
            ]);

            json_result([
                'status' => 'success',
                'message' => 'Đang import dữ liệu, bạn sẽ được thông báo khi hoàn thành...',
                'redirect' => route('backend.languages')
            ]);
        }

        json_result([
            'status' => 'error',
            'message' => 'Không thể tải lên file',
            'redirect' => route('backend.languages')
        ]);
    }
    public function export_languages()
    {
        return (new DonateExport())->download('danh_sach_tang_diem_'. date('d_m_Y') .'.xlsx');
    }

    public function export()
    {
        return (new LanguagesExport())->download('danh_sach_ngon_ngu_'. date('d_m_Y') .'.xlsx');
    }

    public function showModal(Request $request) {

        $model = LanguagesGroups::find($request->id);

        return view('backend.languages.addgroup', [
            'model' => $model
        ]);
    }

}
