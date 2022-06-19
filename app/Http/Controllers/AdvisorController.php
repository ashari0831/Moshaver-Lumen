<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Advisor, Advisor_document, User};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class AdvisorController extends Controller
{

    protected $guarded = [];
    //
    public function show(){
        return response()->json(User::where('id', auth()->user()->id)->with('advisor')->get());
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
        
        auth()->user()->update(['is_advisor' => true]);
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
        return response()->json(["Advisor has been created successfully!"], 201);
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

    public function update_adv($user){
        $this->validate(request(), [
            'meli_code' => 'unique:advisors',
        ]);

        $result = User::find($user)->advisor->update(request()->all());
        if(!($result)){
            return response()->json("update failed!", 400);
        }
        return response()->json(auth()->user()->advisor, 200);
    }

    public function doc_info_for_admin(){
        $docs = Advisor_document::all();

        $docs_info = [];
        foreach( $docs as $doc ){
            array_push($docs_info, [$doc, $doc->advisor->user]);
        }

        return response()->json($docs_info);
    }

    public function advisor_resume_info($advisor){
        $adv = Advisor::find($advisor);
        return response()->json([
            $adv->user, 
            $adv,
            $adv->resumes,
        ]);
    }

    public function destroy($user){
        if(User::find($user)->exists()){
            $user = User::find($user);
            $user->advisor->delete();
            $user->delete();
            return response()->json(["مشاور حذف شد"], 204);
        }

        return response()->json(["چنین مشاوری وجود ندارد"], 400);
    }

    public function list_advisors_for_admin(){
        $result = Advisor::join('rates','rates.advisor_id', '=', 'advisors.id')
                    ->join('users', 'users.id', '=', 'advisors.user_id')
                    ->select('advisor_id', 'advisors.user_id', \DB::raw("avg(rate) as rate"), 'first_name', 'last_name', 'advisors.created_at')
                    ->groupBy('advisor_id')->get();
        return response()->json($result);
    }

    public function admin_creates_advisor(){
        $validated = $this->validate(request(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_number' => 'required|string|unique:users',
            'gender' => 'required|string',
            'year_born' => 'required|string',
            'is_advisor' => 'required|boolean',
            'is_mental_advisor' => 'required|boolean',
            'is_family_advisor' => 'required|boolean',
            'is_sport_advisor' => 'required|boolean',
            'is_healthcare_advisor' => 'required|boolean',
            'is_education_advisor' => 'required|boolean',
            'meli_code' => 'required|string',
            'advise_method' => 'required|string',
            'address' => 'required|string',
            'telephone' => 'required|string',
        ]);

        if($validated['is_advisor']){
            $password = $validated['password'];
            $validated['password'] = app('hash')->make($password);
            $user = User::create($validated);
            $validated['user_id'] = $user->id;
            $advisor = Advisor::create($validated);
        } else {
            $user = User::create($validated);
        }

        return response()->json([$user, $advisor], 201);
    }

    public function admin_show_advisor_profile($advisor){
        return response()->json(Advisor::where('id', $advisor)->with('user')->get());
    }
}
