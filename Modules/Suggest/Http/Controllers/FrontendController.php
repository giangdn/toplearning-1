<?php

namespace Modules\Suggest\Http\Controllers;

use App\Profile;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Suggest\Entities\Suggest;
use Modules\Suggest\Entities\SuggestComment;

class FrontendController extends Controller
{
    public function index()
    {
        Suggest::addGlobalScope(new DraftScope('created_by'));
        $suggest = Suggest::orderBy('id','DESC')->get();

        if (url_mobile()){
            $lay = 'suggest';
            return view('themes.mobile.frontend.suggest.index',[
                'suggest' => $suggest,
                'lay' => $lay
            ]);
        }

        return view('suggest::frontend.index',[
            'suggest' => $suggest
        ]);
    }

    public function getData(Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);
        $search = $request->search;
        $date_from = $request->date_from;
        $date_to = $request->date_to;

        Suggest::addGlobalScope(new CompanyScope());
        $query = Suggest::query();
        if($search) {
            $query->where('name','like','%'.$search.'%');
        }
        if($date_from) {
            $query->where('created_at','>=', date_convert($date_from));
        }
        if($date_to) {
            $query->where('created_at','<=', date_convert($date_to));
        }
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->created_at2 = get_date($row->created_at);
            $row->modal_comment = route('module.suggest.get_comment', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'content' => 'required',
        ], $request, Suggest::getAttributeName());

        $name = $request->input('name');
        $content = $request->input('content');

        $model = new Suggest();
        $model->name = $name;
        $model->content = $content;
        $model->user_id = Auth::id();

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.suggest.index')
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function modalComment($suggest_id){
        $suggest = Suggest::find($suggest_id);
        $profile = Profile::find($suggest->user_id);
        $comments = SuggestComment::where('suggest_id', '=', $suggest->id)->where('user_id',Auth::id())->get();

        if (url_mobile()){
            return view('themes.mobile.frontend.suggest.comment',[
                'suggest' => $suggest,
                'comments' => $comments,
                'profile' => $profile,
            ]);
        }

        return view('suggest::modal.comment', [
            'suggest' => $suggest,
            'comments' => $comments,
            'profile' => $profile,
        ]);
    }

    public function saveComment($suggest_id, Request $request) {
        $this->validateRequest([
            'content' => 'required',
        ], $request, SuggestComment::getAttributeName());

        $content = $request->input('content');

        $model = new SuggestComment();
        $model->content = $content;
        $model->suggest_id = $suggest_id;
        $model->user_id = Auth::id();

        if ($model->save()) {
            $profile = Profile::find($model->user_id);
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'user' => $profile->lastname .' '. $profile->firstname,
                'created_at2' => get_date($model->created_at, 'H:i:s d/m/Y'),
                'content' => $model->content,
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }
}
