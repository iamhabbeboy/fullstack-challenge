<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise;
use Illuminate\Support\Facades\Log;

class WeatherService {

    private Client $httpClient;

    public function __construct(Client $client)
    {
        $this->httpClient = $client;
    }

    /**
     * @throws \Throwable
     */
    public function get($locations): array
    {
        $promises = [];
        foreach($locations as $location) {
            $loc = 'lat='. $location->latitude . '&lon=' . $location->longitude;
            $key = $location->id;
            $url = config('weather.base_url') . "?" . $loc . "&appid=" . config('weather.api_key') ;
            $promise = $this->httpClient->getAsync($url);
            $promise->then(
                null,
                function (RequestException $e) use ($url) {
                    $errorMessage = "Request to $url failed: " . $e->getMessage();
                    Log::error($errorMessage);
                }
            );
            $promises[$key] = $promise;
        }

        $responses = Promise\Utils::unwrap($promises);

        return Promise\Utils::settle($promises)->wait();
    }
}
