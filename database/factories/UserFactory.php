<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'gender' => 'm',
            'phone_number' => $this->faker->phoneNumber,
            'password' => '$2y$10$msDPndIziyH78/dqkOn6U.ffLgIc38MLKhV6RffvV2m4Lpbtcr6am',
            'email' => $this->faker->unique()->safeEmail,
            // 'is_staff' => true
        ];
    }
}
