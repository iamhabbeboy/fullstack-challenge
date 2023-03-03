<?php

namespace Tests\Feature;

use App\Http\Controllers\UserController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetches_all_users()
    {
        $users = [
            "message" => "all systems are a go",
            "users" => [
                ['id' => 1, 'name' => 'John Doe','latitude' => 37.7749, 'longitude' => -122.4194],
                ['id' => 2, 'name' => 'Jane Doe','latitude' => 37.7749, 'longitude' => -122.4194],
            ]
        ];

        Http::fake([
            'http://localhost/' => Http::response($users, 200),
        ]);

        $apiResponse = Http::get('http://localhost/');

        $response = $apiResponse->json();

        $this->assertEquals("all systems are a go", $response["message"]);

        $this->assertCount(2, $users["users"]);

        $this->assertEquals(37.7749, $response["users"][0]["latitude"]);
    }

    public function test_fetches_a_specific_user()
    {
        $user = ['id' => 1, 'name' => 'John Doe', 'latitude' => 37.7749, 'longitude' => -122.4194];

        Http::fake([

            'http://localhost/*' => Http::response($user, 200),

        ]);
        $apiResponse = Http::get('http://localhost/1');

        $response = $apiResponse->json();

        $this->assertEquals(200, $apiResponse->status());

        $this->assertEquals($user, $response);
    }
}
