<?php
namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;
use Faker\Factory;

class BeingAdminTest extends TestCase
{
    use DatabaseTransactions;

    function setUp(): void
    {
        parent::setUp();
        // $this->faker = Factory::create();
        $user = User::factory()->create();
        $this->user = $user;
        
    }

    public function test_unknown_user_cannot_list_users()
    {
        $res = $this->json('GET', '/api/v1/admin/list-users');
        $res->assertResponseStatus(401);
    }

    public function test_not_admin_user_cannot_list_users()
    {
        $this->actingAs($this->user, 'api');
        $res = $this->json('GET', '/api/v1/admin/list-users');
        $res->assertResponseStatus(403);
        
    }

    public function test_admin_can_list_users()
    {
        $admin_user = User::find(1);
        $this->actingAs($admin_user, 'api');
        $res = $this->json('GET', '/api/v1/admin/list-users');
        $res->assertResponseStatus(200);

    }

}
