<?php

namespace Modules\TrainingUnit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class BackendController extends Controller
{
    public function index()
    {
        return view('trainingunit::backend.training_unit.index');
    }

}
