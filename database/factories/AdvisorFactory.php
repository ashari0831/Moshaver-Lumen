<?php

namespace Database\Factories;

use App\Models\Advisor;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvisorFactory extends Factory
{
    protected $model = Advisor::class;

    public function definition(): array
    {
    	return [
    	    'is_mental_advisor' => true,
            'is_family_advisor' => false,
            'is_sport_advisor' => false,
            'is_healthcare_advisor' => false,
            'is_education_advisor' => true,
            'meli_code' => strval($this->faker->unique()->randomNumber($nbDigits = 9, $strict = true)),
            'advise_method' => 'on',
            'address' => 'required',
            'telephone' => 'required',
            // 'user_id' => $this->user_id,
        ];
    }
}
