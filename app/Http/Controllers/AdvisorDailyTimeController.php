<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advisor_daily_time;

class AdvisorDailyTimeController extends Controller
{
    public function store(){
        $validated = $this->validate([
            'job_time' => 'required|Array'
        ]);

        $job_time = Advisor_daily_time::create([
            'advisor_id' => auth()->user()->advisor->id,
            'job_time' => $validated['job_time'],
        ]);

        return response()->json([$job_time], 201);
    }

    public function show(){
        $job_time = Advisor_daily_time::where('advisor_id', auth()->user()->advisor->id)->get();
        return response()->json([$job_time], 200);
    }

    public function update(){
        $validated = $this->validate([
            'job_time' => 'required|Array'
        ]);

        $job_time = Advisor_daily_time::where('advisor_id', auth()->user()->advisor->id)->update([
            'job_time' => $validated['job_time'],
        ]);
        if($job_time){
            return response()->json([$job_time], 200);
        }

        return response()->json(['update failed'], 400);
    }

    public function admin_show($advisor){
        $job_time = Advisor_daily_time::where('advisor_id', $advisor)->get();
        return response()->json([$job_time], 200);
    }

    public function admin_update(){
        $validated = $this->validate([
            'job_time' => 'required|Array'
        ]);

        $job_time = Advisor_daily_time::where('advisor_id', $advisor)->update([
            'job_time' => $validated['job_time'],
        ]);
        if($job_time){
            return response()->json([$job_time], 200);
        }

        return response()->json(['update failed'], 400);
    }
}
