<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Categories\Titles;
use App\Profile;
use Illuminate\Http\Request;
use Matrix\Builder;
use Modules\ConvertTitles\Entities\ConvertTitles;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;
use Modules\Potential\Entities\Potential;
use Modules\Quiz\Entities\Quiz;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $items = $this->getItems($request);
        return view('themes.mobile.frontend.search', [
            'items' => $items
        ]);
    }

    public function getItems(Request $request) {
        $search = $request->get('q');
        $type = $request->get('type');

        if ($type){
            /*Khóa học online tháng hiện tại*/
            if ($type == 1){
                $query = OnlineCourse::query();
                $query->where('status', '=', 1);
                $query->where('isopen', '=', 1);
                $query->where(\DB::raw('month(start_date)'), '=', date('m'));
                $query->orderByDesc('id');

                $items = $query->paginate(20);
                $items->appends($request->query());

                return $items;
            }
            /*Khóa học online sắp tổ chức*/
            if ($type == 2){
                $query = OnlineCourse::query();
                $query->where('status', '=', 1);
                $query->where('isopen', '=', 1);
                $query->where('start_date', '>', date('Y-m-d'));
                $query->orderByDesc('id');

                $items = $query->paginate(20);
                $items->appends($request->query());

                return $items;
            }

            /*Khóa học offline sắp tổ chức*/
            if ($type == 3){
                $query = OfflineCourse::query();
                $query->where('status', '=', 1);
                $query->where('isopen', '=', 1);
                $query->where('start_date', '>', date('Y-m-d'));
                $query->orderByDesc('id');

                $items = $query->paginate(20);
                $items->appends($request->query());

                return $items;
            }

            /*Kỳ thi trong tháng hiện tại*/
            if ($type == 4){
                $dbprefix = \DB::getTablePrefix();

                $query = Quiz::query();
                $query->select(['a.*']);
                $query->from('el_quiz AS a');
                $query->where(\DB::raw('(select MONTH(MIN(start_date)) from '.$dbprefix.'el_quiz_part where quiz_id = '.$dbprefix.'a.id)'), '=', date('m'));
                $query->orderByDesc('a.id');

                $items = $query->paginate(20);
                $items->appends($request->query());

                return $items;
            }

            /*Khóa học theo chương trình khung*/
            if ($type == 5){
                $user_convert_titles = ConvertTitles::query()
                    ->where('user_id','=',\Auth::id())
                    ->where('end_date','>',date('Y-m-d H:i:s'))
                    ->first();

                $user_potential = Potential::query()
                    ->where('user_id','=',\Auth::id())
                    ->where('end_date','>',date('Y-m-d H:i:s'))
                    ->first();

                if ($user_convert_titles){
                    $roadmap = 'el_convert_titles_roadmap';
                    $title = Titles::find($user_convert_titles->title_id);
                }
                elseif ($user_potential){
                    $roadmap = 'el_potential_roadmap';
                    $user = Profile::find(\Auth::id());
                    $title = Titles::where('code','=', $user->title_code)->first();
                }
                else{
                    $roadmap = 'el_trainingroadmap';
                    $user = Profile::find(\Auth::id());
                    $title = Titles::where('code','=', $user->title_code)->first();
                }

                $subQuery = \DB::table('el_course_register_view as a1')
                    ->join('el_course_view as a2', function ($join){
                        $join->on('a1.course_id','=','a2.course_id');
                        $join->on('a1.course_type','=','a2.course_type');
                    })
                    ->where('a1.user_id','=',\Auth::id())
                    ->groupBy(['a2.subject_id','a2.course_type'])
                    ->select([
                        \DB::raw('MAX('.\DB::getTablePrefix().'a2.course_id) as course_id'),
                        'a2.subject_id',
                        'a2.course_type'
                    ]);

                $query = \DB::query();
                $query->select([
                    'c.*'
                ]);
                $query->from("$roadmap AS a");
                $query->leftJoinSub($subQuery,'b', function ($join){
                    $join->on('b.course_type', '=', 'a.training_form');
                    $join->on('b.subject_id', '=', 'a.subject_id');
                });
                $query->leftJoin('el_course_view AS c', function ($join){
                    $join->on('c.course_id', '=', 'b.course_id');
                    $join->on('c.course_type', '=', 'b.course_type');
                });
                $query->leftJoin('el_course_register_view as d',function ($join){
                    $join->on('d.course_id', '=', 'c.id');
                    $join->on('d.course_type', '=', 'c.course_type')->where('d.user_id', '=', \Auth::id());
                });
                $query->where('a.title_id','=', $title->id);

                $items = $query->paginate(20);
                $items->appends($request->query());

                return $items;
            }

            /*khóa online đang học*/
            if ($type == 6){
                $query = OnlineCourse::query();
                $query->select(['a.*'])
                    ->from('el_online_course as a')
                    ->leftJoin('el_online_register as b', 'b.course_id', '=', 'a.id')
                    ->where('b.user_id', '=', \Auth::id())
                    ->where('b.status', '=', 1)
                    ->where('a.status', '=', 1)
                    ->where('a.isopen', '=', 1)
                    ->whereNotExists(function ($subquery) {
                        $subquery->select(['id'])
                            ->from('el_online_result')
                            ->whereColumn('register_id', '=', 'b.id')
                            ->where('result', '=', 1);
                    })
                    ->where(function ($sub){
                        $sub->whereNull('a.end_date');
                        $sub->orWhere('a.end_date', '>', date('Y-m-d'));
                    })
                    ->where('a.start_date', '<', date('Y-m-d'));
                $query->orderByDesc('id');

                $items = $query->paginate(20);
                $items->appends($request->query());

                return $items;
            }

            /*Kết quả đào tạo*/
            if ($type == 7){
                $prefix = \DB::getTablePrefix();
                $query = \DB::table('el_course_view as a')->select(['a.*'])
                    ->join('el_course_register_view as b',function($join){
                        $join->on('a.course_id','=','b.course_id');
                        $join->on('a.course_type','=','b.course_type');
                    })
                    ->where('b.user_id','=', \Auth::id())
                    ->where('a.status', '=', 1)
                    ->where('b.status', '=', 1)
                    ->where('a.isopen', '=', 1)
                    ->orderBy('a.id', 'desc');

                $items = $query->paginate(20);
                $items->appends($request->query());

                return $items;
            }
        }else{
            $query = OnlineCourse::query();
            $query->where('status', '=', 1);
            $query->where('isopen', '=', 1);

            if ($search) {
                $query->where(function ($subquery) use ($search) {
                    $subquery->orWhere('code', 'like', '%'. $search .'%');
                    $subquery->orWhere('name', 'like', '%'. $search .'%');
                });
            }

            $query->orderByDesc('id');
            $items = $query->paginate(20);
            $items->appends($request->query());

            return $items;
        }
    }
}
