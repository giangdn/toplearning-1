<?php

namespace Modules\TrainingByTitle\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Profile;
use Modules\TrainingByTitle\Entities\TrainingByTitleCategory;

class TrainingByTitleController extends Controller
{
    public function index(){
        $user = Profile::whereUserId(\Auth::id())->first();
        $title = @$user->titles;
        if ($user->date_title_appointment){
            $start_date = $user->date_title_appointment;
        }elseif ($user->effective_date){
            $start_date = $user->effective_date;
        }else{
            $start_date = $user->join_company;
        }

        $training_by_title_category = TrainingByTitleCategory::where('title_id', '=', @$title->id)->get();
        if (url_mobile()){
            return view('trainingbytitle::mobile.training_by_title', [
                'training_by_title_category' => $training_by_title_category,
                'start_date' => $start_date
            ]);
        }

        return redirect()->route('module.frontend.user.training_by_title');
    }
}
