<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Repository\Contracts\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(): UserCollection
    {
        if(Cache::has('users')) {
            return new UserCollection(Cache::get('users'));
        }

        $response = $this->userRepository->getAll(['limit' => 20]);

        $expiration = now()->addMinutes(30);
        Cache::put('users', $response, $expiration);

        return new UserCollection($response);
    }

    public function show(int $userId): JsonResponse|UserResource
    {
        if(Cache::has('user-' . $userId )) {
            return new UserResource(Cache::get('users' . $userId));
        }

        try {
            $response = $this->userRepository->get($userId);
        }catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }

        $expiration = now()->addMinutes(30);
        Cache::put('user-' . $userId, $response, $expiration);

        return new UserResource($response);
    }
}
