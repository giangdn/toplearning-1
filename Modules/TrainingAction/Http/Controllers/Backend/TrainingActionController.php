<?php

namespace Modules\TrainingAction\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class TrainingActionController extends Controller
{
    public function index()
    {
        return view('trainingaction::backend.index');
    }
}
