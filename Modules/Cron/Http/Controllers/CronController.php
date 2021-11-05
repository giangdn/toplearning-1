<?php

namespace Modules\Cron\Http\Controllers;

use App\Console\Commands\BaseCommand;
use Composer\Autoload\ClassMapGenerator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Cron\Entities\Cron;
use Modules\Cron\Http\Requests\CrontRequest;
use mysql_xdevapi\Collection;

class CronController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('cron::backend.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }
    public function getData(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort','id');
        $order = $request->input('order','desc');
        $offset = $request->input('offset',0);
        $limit = $request->input('limit',20);
//        MoveTrainingProcess::addGlobalScope(new DraftScope());
        $query = Cron::query();
        $query->select('*')->get();
        if($search){
            $query->where(function($sub_query) use ($search){
                $sub_query->orWhere('name','like','%' . $search . '%');
                $sub_query->orWhere('description','like','%' . $search . '%');
            });
        }

        $count = $query ->count();
        $query -> orderBy( $sort,$order);
        $query ->offset($offset);
        $query->limit($limit);
        $rows = $query ->get();
        foreach ($rows as $row) {
            $row->last_run = get_datetime($row->last_run);
            $row->edit = route('module.cron.edit',["id"=>$row->id]);
            $row->duration = $row->start_time.' - '.$row->end_time;
            $row->status = $row->enabled==1? '<span class="text-success">'.trans('backend.enable').'</span>':'<span class="text-danger">'.trans('backend.disable').'</span>' ;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function create()
    {
        $commands = $this->getCommands();
        return view('cron::backend.create',
            [
                'commands' =>$commands,
            ]
        );
    }
    public function getCommands($id=0){
        $class =ClassMapGenerator::createMap(app_path('Console/Commands/'));
        foreach ($class as $index => $item) {
            $_class = new $index();
            $pros = new \ReflectionProperty($_class,'signature');
            $pros->setAccessible(true);
            $code =$pros->getValue($_class);
//            $exists = Cron::where('command',$code)->where('id','<>',$id)->exists();
//            if ($exists)
//                continue;
            $pros = new \ReflectionProperty($index,'description');
            $pros->setAccessible(true);
            $name = $pros->getValue($_class);
            $pros = new \ReflectionProperty($index,'hidden');
            $pros->setAccessible(true);
            $hidden =$pros->getValue($_class);
            if (!$hidden)
                $commands_arr[] =(object)['code'=>$code,'name'=>$name];
        }
        return collect($commands_arr);
    }
    public function store(CrontRequest $request)
    {
        $model = new Cron();
        $model->fill($request->all());
        $model->expression = $request->minute.' '.$request->hour.' '.$request->day.' '.$request->month.' '.$request->day_of_week;
        $command = $this->getCommands();
        $model->description=$command->where('code','=',$request->command)->first()->name;
        $model->enabled=1;
        if($model->save()){
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect'=>route('module.cron.edit',['id'=>$model->id])
            ]);
        }
        json_message(trans('lageneral.save_error'), 'error');
    }
    public function show($id)
    {
        return view('cron::show');
    }
    public function edit($id)
    {
        $commands =Cron::getAllCommand();
        $model = Cron::findOrFail($id);
        return view('cron::backend.edit',
        [
            'commands' =>$commands,
            'model' =>$model,
        ]
        );
    }

    public function update(CrontRequest $request, $id)
    {
        $model = Cron::findOrFail($id);
        $model->fill($request->all());
        $model->expression = $request->minute.' '.$request->hour.' '.$request->day.' '.$request->month.' '.$request->day_of_week;
        $model->enabled = $request->enabled?1:0;
        if($model->save()){
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect'=>route('module.cron.edit',['id'=>$model->id])
            ]);
        }
        json_message(trans('lageneral.save_error'), 'error');
    }

    public function destroy(Request $request)
    {
        $ids = $request->input('ids', null);
        Cron::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }


}
