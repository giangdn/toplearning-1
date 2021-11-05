<?php

namespace App\Http\Controllers\Frontend;

use App\Boxmap;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\FormMapRequest;

class GoogleMapController extends Controller
{
    public function index()
    {
        $boxmap = Boxmap::all();
       
        $dataMap  = Array();
        $dataMap['type']='FeatureCollection';
        $dataMap['features']=array();
        foreach($boxmap as $value){
            $feaures = array();
            $feaures['type']='Feature';
            $geometry = array("type"=>"Point","coordinates"=>[$value->lng, $value->lat]);
            $feaures['geometry']=$geometry;
            $properties=array('title'=>$value->title,"description"=>$value->description);
            $feaures['properties']= $properties;
            array_push($dataMap['features'],$feaures);
        }
        return view('frontend.google_map',['dataArray' => json_encode($dataMap), 'boxmaps' => $boxmap]);
    }

    public function store(Request $request)
    {
    //    $validated = $request->validated();
       Boxmap::create($request->all());
       return redirect('/google-map')->with('success',"Add map success!");
    }
}
