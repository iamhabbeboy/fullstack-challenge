<?php

namespace App\Repository\Users;

use App\Models\User;
use App\Repository\Contracts\UserRepositoryInterface;
use App\Services\WeatherService;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    private User $user;
    private WeatherService $weatherService;

    public function __construct(User $user, WeatherService $weather)
    {
        $this->user = $user;
        $this->weatherService = $weather;
    }

    public function get(int $id): Model|Builder
    {
        return $this->user->query()->findOrFail($id);
    }

    /**
     * @throws \Throwable
     */
    public function getAll(array $filter = ['limit' => 20]): Collection
    {
        $users = $this->user->query()->get(['latitude', 'longitude', 'id']);
        $userResponse = Collect([]);
        $responses = $this->weatherService->get($users);

        foreach($responses as $userId => $response) {
            $user = $this->user->query()->findOrFail($userId);
            $userResponse[$userId] = $user;
            if($response["state"] === PromiseInterface::FULFILLED) {
                $result = json_decode((string) $response["value"]->getBody(), true);
                $user->weather = $result;
                $userResponse[$userId] = $user;
            }
        }
        return $userResponse;
    }
}
