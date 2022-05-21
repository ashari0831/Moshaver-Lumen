<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Advisor, Advisor_document};
use Illuminate\Support\Facades\File;


class AdvisorController extends Controller
{

    protected $guarded = [];
    //
    public function show(){
        return response()->json(auth()->user()->advisor);
    }

    public function store(Request $req){
        $validated_data = $this->validate($req, [
            'is_mental_advisor' => 'required',
            'is_family_advisor' => 'required',
            'is_sport_advisor' => 'required',
            'is_healthcare_advisor' => 'required',
            'is_education_advisor' => 'required',
            'meli_code' => 'bail|required|unique:advisors',
            'advise_method' => 'required',
            'address' => 'required',
            'telephone' => 'required',
        ]);
        
        
        Advisor::create([
            'user_id'=> auth()->user()->id,
            'is_mental_advisor'=> $validated_data['is_mental_advisor'],
            'is_family_advisor'=> $validated_data['is_family_advisor'],
            'is_sport_advisor'=> $validated_data['is_sport_advisor'],
            'is_healthcare_advisor'=> $validated_data['is_healthcare_advisor'],
            'is_education_advisor'=> $validated_data['is_education_advisor'],
            'meli_code'=> $validated_data['meli_code'],
            'advise_method'=> $validated_data['advise_method'],
            'address'=> $validated_data['address'],
            'telephone'=> $validated_data['telephone'],
        ]);
        return response()->json("Advisor has been created successfully!");
    }

    public function index(){
        $advisors = Advisor::all();
        $adv_infos = [];
        foreach ($advisors as $advisor){
            $adv_info = [];
            array_push($adv_info, $advisor);
            array_push($adv_info, $advisor->user);
            array_push($adv_infos, $adv_info);
        }
        return response()->json($adv_infos);
    }

    public function update(){
        $this->validate(request(), [
            'meli_code' => 'unique:advisors',
        ]);

        $result = auth()->user()->advisor->update(request()->all());
        if(!($result)){
            return response()->json("update failed!", 400);
        }
        return response()->json(auth()->user()->advisor, 200);
    }

}
