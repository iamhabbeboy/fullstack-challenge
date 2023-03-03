<?php

namespace Tests\Unit;

use App\Services\WeatherService;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Promise;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;

class WeatherServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('weather.base_url', 'https://api.openweathermap.org/data/2.5/weather');
        Config::set('weather.api_key', 'API_KEY');

    }

    public function test_retrieve_weather_report()
    {
        $promise = new Promise(function () use (&$promise) {
            $promise->resolve('{"temperature": 15}');
        });

        $promise->wait(false);

        $httpClientMock = $this->mock(Client::class);
        $httpClientMock
            ->shouldReceive('getAsync')
            ->andReturn($promise);

        $locations = collect([
            (object) ['id' => 1, 'latitude' => 37.7749, 'longitude' => -122.4194],
            (object) ['id' => 2, 'latitude' => 40.7128, 'longitude' => -74.0060],
            (object) ['id' => 3, 'latitude' => 51.5074, 'longitude' => -0.1278],
        ]);

        $weatherService = new WeatherService($httpClientMock);
        $weatherData = $weatherService->get($locations);

        $this->assertCount(3, $weatherData);
        $this->assertEquals("fulfilled", $weatherData[1]["state"]);
        $decodeResult = (array)json_decode($weatherData[1]["value"]);

        $this->assertArrayHasKey("temperature", $decodeResult);
        $this->assertEquals(15, $decodeResult["temperature"]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }

}
