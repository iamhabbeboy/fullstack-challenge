<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repository\Users\UserRepository;
use App\Services\WeatherService;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use Psr\Http\Message\StreamInterface;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private UserRepository $userRepository;
    private WeatherService|MockInterface $weatherService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->id = $this->faker->numberBetween(1, 10);
        $this->user = User::factory()->create(['id' => $this->id]);

        $stream = $this->mock(StreamInterface::class);
        $stream->shouldReceive('getBody')
            ->andReturn('{"temperature": 15}');

        $promise = new Promise\Promise(function () use ($stream, &$promise) {
            $promise->resolve($stream);
        });

        $promise->wait();
        $promise = Promise\Utils::settle($promise)->wait();

        $this->response = [$this->id => $promise[0]];

        $this->weatherService = $this->mock(WeatherService::class);
    }

    /**
     * @throws \Throwable
     */
    public function test_retrieve_a_user_by_id()
    {

        $this->weatherService->shouldReceive('get', [$this->user->get()])
            ->andReturn($this->response);


        $this->userRepository = new UserRepository(new User(), $this->weatherService);

        $result = $this->userRepository->get($this->id);

        $output = $result->toArray();

        $this->assertEquals(15, $output["weather"]["temperature"]);

        $this->assertEquals($this->user->id, $output["id"]);
    }

    /**
     * @throws \Throwable
     */
    public function test_retrieve_all_users()
    {
        $this->weatherService->shouldReceive('get', [$this->user->get()])
            ->andReturn($this->response);


        $this->userRepository = new UserRepository(new User(), $this->weatherService);

        $result = $this->userRepository->getAll();

        $output = $result->toArray();
        $this->assertCount(1, $output);
        $this->assertEquals(15, $output[0]["weather"]["temperature"]);
        $this->assertEquals($this->user->id, $output[0]["id"]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }
}
