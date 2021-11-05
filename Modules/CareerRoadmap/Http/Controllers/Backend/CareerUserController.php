<?php

namespace Modules\CareerRoadmap\Http\Controllers\Backend;

use App\Models\Categories\Titles;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CareerRoadmap\Entities\CareerRoadmap;
use App\Profile;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;

class CareerUserController extends Controller
{
    public function index($user_id) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $user = User::findOrFail($user_id);
        $profile = Profile::where('user_id', '=', $user_id)->first(['title_code']);
        $title = Titles::where('code', '=', $profile->title_code)->first(['id']);
        $roadmaps = CareerRoadmap::where('title_id', '=', @$title->id)
            ->get(['id', 'name']);
        
        return view('careerroadmap::backend.user.index', [
            'roadmaps' => $roadmaps,
            'user_id' => $user_id,
            'user' => $user,
            'full_name' => $user->lastname . ' ' . $user->firstname,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }
    
    public function getCourses($user_id, $title_id, Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        
        $query = TrainingRoadmap::query();
        $query->with('subject');
        $query->select([
            'a.id',
            'b.code AS subject_code',
            'b.name AS subject_name',
            'c.name AS title_name',
        ]);
        $query->from('el_trainingroadmap AS a');
        $query->leftJoin('el_subject AS b', 'b.id', '=', 'a.subject_id');
        $query->leftJoin('el_titles AS c', 'c.id', '=', 'a.title_id');
        $query->where('a.title_id', '=', $title_id);
        
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->result = $row->subject->isCompleted($user_id) ? 1 : 0;
        }
        
        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }
}
