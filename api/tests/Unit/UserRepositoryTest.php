<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repository\Users\UserRepository;
use App\Services\WeatherService;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private UserRepository $userRepository;
    private WeatherService|MockInterface $weatherService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->weatherService = $this->mock(WeatherService::class);
        $this->userRepository = new UserRepository(new User(), $this->weatherService);
    }

//    public function test_retrieve_a_user_by_id()
//    {
//        // Arrange
//        $id = $this->faker->numberBetween(1, 10);
//        $user = User::factory()->create(['id' => $id]);
//        $weather = ['state' => 'fulfilled', 'value' => '{"weather": "sunny"}'];
//        $locations = collect([
//            (object) ['id' => 1, 'latitude' => 37.7749, 'longitude' => -122.4194],
//            (object) ['id' => 2, 'latitude' => 40.7128, 'longitude' => -74.0060],
//            (object) ['id' => 3, 'latitude' => 51.5074, 'longitude' => -0.1278],
//        ]);
//
//        $this->weatherService->shouldReceive('get')
//            ->with($user->get())
//            ->andReturn([1 => []]);
//
//        $result = $this->userRepository->getAll();
//        // Assert
//        $this->assertEquals($id, $result->first()->id);
////        $this->assertEquals('sunny', $result->weather['weather']);
//    }

//    public function it_can_retrieve_all_users()
//    {
//        // Arrange
//        $limit = 2;
//        User::factory()->count($limit)->create();
//        $users = User::all();
//        $weather = [
//            1 => ['state' => 'fulfilled', 'value' => '{"weather": "sunny"}'],
//            2 => ['state' => 'fulfilled', 'value' => '{"weather": "cloudy"}'],
//        ];
//
//        $this->weatherService
//            ->shouldReceive('get')
//            ->once()
//            ->with($users)
//            ->andReturn($weather);
//
//        // Act
//        $result = $this->userRepository->getAll(['limit' => $limit]);
//
//        // Assert
//        $this->assertInstanceOf(Collection::class, $result);
//        $this->assertCount($limit, $result);
//        $this->assertEquals('sunny', $result->get(0)->weather['weather']);
//        $this->assertEquals('cloudy', $result->get(1)->weather['weather']);
//    }

    public function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }
}
