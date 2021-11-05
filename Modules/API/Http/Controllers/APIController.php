<?php

namespace Modules\API\Http\Controllers;

use App\Certificate;
use App\Models\Categories\Absent;
use App\Models\Categories\Province;
use App\Models\Categories\TitleRank;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\API\Entities\API;
use Modules\User\Entities\ProfileLevel;
use Modules\User\Entities\User;
use Modules\User\Entities\WorkingProcess;

class APIController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        if ($request->ajax()){
            $sort = $request->input('sort', 'start_date');
            $order = $request->input('order', 'desc');
            $offset = $request->input('offset', 0);
            $limit = $request->input('limit', 20);

            $query = API::query();
            $query->select('*');
            $count = $query->count();
            $query->orderBy($sort, $order);
            $query->offset($offset);
            $query->limit($limit);
            $rows = $query->get();
            foreach($rows as $item){
                $item->updated_date = get_datetime($item->updated_at);
                $item->duration = get_datetime($item->start_time,'H:i:s').' - '.get_datetime($item->end_time,'H:i:s');
            }
            json_result(['total' => $count, 'rows' => $rows]);
        }
        return view('api::backend.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->input('id',0);
        $api = API::findOrFail($id);
        API::find($id)->update(['error'=>1,'start_time'=>now()]);
        if ($id==1) // loại nghỉ
            Absent::syncAPIAbsent($api->url,$api->code);
        elseif($id==2) // cấp bậc chức danh
            TitleRank::syncAPITitleRank($api->url,$api->code);
        elseif($id==3) // chức danh
            Titles::syncAPITitle($api->url,$api->code);
        elseif($id==4) // tỉnh thành
            Province::syncAPIProvince($api->url,$api->code);
        elseif($id==5) // trình độ
            Certificate::syncAPICertificate($api->url,$api->code);
        elseif($id==6) // đơn vị
            Unit::syncAPIUnit($api->url,$api->code);
        elseif ($id==7)// nhân viên
            User::syncAPIUser($api->url,$api->code);
        elseif ($id==8)// Quá trình công tác
            WorkingProcess::syncAPIWorkingProcess($api->url,$api->code);
        API::find($id)->update(['error'=>0,'end_time'=>now()]);
        json_success();
    }
}
