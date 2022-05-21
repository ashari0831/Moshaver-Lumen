<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Advisor_history, Advisor};

class AdvisorHistoryController extends Controller
{
    public function index(){
        return response()->json(auth()->user()->advisor->resumes);
    }

    public function show($advisor){
        return response()->json(Advisor::find($advisor)->resumes);
    }

    public function update($resume){
        if(Advisor_history::find($resume)->update(request()->all())){
            return response()->json(Advisor_history::find($resume));
        }
        
    }


}
