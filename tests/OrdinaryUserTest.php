<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;
use Faker\Factory;


class OrdinaryUserTest extends TestCase
{
    use DatabaseTransactions;

    function setUp(): void
    {
        parent::setUp();
        
        $user = User::factory()->create();
        $this->user = $user;
        
        // print_r(auth()->user());
        
    }
    
    

    /**
     * A basic test example.
     *
     * @return void
     */
    /**public function test_that_base_endpoint_returns_a_successful_response()
    {
        $this->get('/');

        $this->assertEquals(
            $this->app->version(), $this->response->getContent()
        );
    }
    */

    // public function test_that_user_can_register()
    // {
    //     $user = User::factory()->create();
        
    //     $this->assertEquals(200, $res->status());
    // }
    public function test_see_if_user_exists()
    {
        $this->seeInDatabase('users', ['email' => $this->user->email]);
    }

    public function test_user_can_see_profile()
    {
        $this->actingAs($this->user, 'api');
        $res = $this->json('GET', '/api/v1/user-profile');
        $res->assertResponseStatus(200);
    }
    public function test_user_can_be_advisor()
    {
        $faker = Factory::create();
        $this->actingAs($this->user, 'api');

        $res = $this->json('POST', '/api/v1/create-advisor', [
            'is_mental_advisor' => true,
            'is_family_advisor' => false,
            'is_sport_advisor' => false,
            'is_healthcare_advisor' => false,
            'is_education_advisor' => true,
            'meli_code' => strval($faker->unique()->randomNumber($nbDigits = 9, $strict = true)),
            'advise_method' => 'on',
            'address' => 'required',
            'telephone' => 'required',
        ]);
        // print_r($res);

        $res->assertResponseStatus(201);
    }

    
}
