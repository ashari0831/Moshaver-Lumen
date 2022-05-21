<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Viewer, Reservation};
use Carbon\Carbon;

class StatisticalController extends Controller
{

    public function get_statistical_data(){
        return response()->json([
            'man_percentage' => $this-> get_gender_percentage()['male'],
            'woman_percentage' => $this-> get_gender_percentage()['female'],
            'daily_view' => $this-> get_count_daily_viewers(),
            'monthly_view' => $this-> get_count_monthly_viewers(),
            'yearly_view' => $this-> get_count_yearly_viewers(),
            'completed_session' => $this-> get_count_completed_sessions(),
            'reserved_session' => $this-> get_count_reserved_sessions(),
            'session_hours' => $this-> get_sum_session_hours(),
            'daily_user_joined' => $this-> get_count_daily_users_joined(false),
            'daily_advisor_joined' => $this-> get_count_daily_users_joined(true),
        ]);
    }

    public function get_gender_percentage(){
        $num_of_male = User::where('gender', 'M')->count();
        $num_of_female = User::where('gender', 'F')->count();
        $male_perc = ($num_of_male / ($num_of_male + $num_of_female)) * 100;
        $female_perc = ($num_of_female / ($num_of_male + $num_of_female)) * 100;

        return [
            'male' => $male_perc,
            'female' => $female_perc
        ];
    }

    public function get_count_daily_viewers(){
         return Viewer::where([
            ['created_at', '<', Carbon::now()],
            ['created_at', '>', Carbon::now()->subDay()],
        ])->count();
    }
    public function get_count_monthly_viewers(){
        return Viewer::where([
            ['created_at', '<', Carbon::now()],
            ['created_at', '>', Carbon::now()->subMonth()],
        ])->count();
    }
    public function get_count_yearly_viewers(){
        return Viewer::where([
            ['created_at', '<', Carbon::now()],
            ['created_at', '>', Carbon::now()->subYear()],
        ])->count();
    }

    public function get_count_daily_users_joined($is_advisor){
        $joined_count = [];
        for ($i = 1; $i < 31; $i++){
            $ac = User::where([
                ['created_at', '<', Carbon::now()->subDays(i)],
                ['created_at', '>', Carbon::now()->subDays(i + 1)],
                ['is_advisor', $is_advisor],
            ])->count();
            array_push($joined_count, $ac);
        }
        return $joined_count;
    }

    public function get_count_completed_sessions(){
        return Reservation::where('end_session_datetime', '<', Carbon::now)->count();
    }
    public function get_count_reserved_sessions(){
        return Reservation::all()->count();
    }

    public function get_sum_session_hours(){
        $reservations = Reservation::all();
        $sum = 0;
        foreach ($reservations as $res) {
            $sum += abs( intval($res->end_session_datetime - $res->reservation_datetime) );
        }
        return $sum;
    }
    
}
