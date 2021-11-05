<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EvaluationFormController extends Controller
{
    public function index() {
        return view('evaluationform::index');
    }
}
