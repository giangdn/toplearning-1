<?php

namespace App\Http\Controllers\Backend;

use App\Automail;
use App\Http\Controllers\Controller;
use App\MailTemplate;
use App\Models\MailHistory;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;

class MailHistoryController extends Controller
{
    public function index() {
        return view('backend.mail_history.index');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        MailHistory::addGlobalScope(new DraftScope());
        $query = MailHistory::query();
        $query->select([
            'id',
            'list_mail',
            'params',
            'send_time',
            'status',
            'name',
            'content',
            'code'
        ]);
        if ($search) {
            $query->where(function ($subquery) use ($search){
                $subquery->orWhere('name', 'like', '%'. $search .'%');
                $subquery->orWhere('code', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->emails = explode(', ', $row->list_mail);
            $row->content = $this->mapParams($row->content, $row->params);
            $row->send_time = get_date($row->send_time, 'H:i:s d/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function mapParams($content, $params) {
        $params = json_decode($params);
        foreach ($params as $key => $param) {
            $content = str_replace('{'. $key .'}', $param, $content);
        }

        return $content;
    }

    public function getParams($params, $key) {
        $params = json_decode($params);
        if (isset($params->{$key})) {
            return $params->{$key};
        }

        return null;
    }
}
