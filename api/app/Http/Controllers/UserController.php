<?php

namespace App\Http\Controllers;

use App\Repository\Contracts\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

//use Cache;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(): JsonResponse
    {
        if(Cache::has('users')) {
            return response()->json([
                'message' => 'all systems are a go',
                'users' => Cache::get('users'),
            ]);
        }

        $response = $this->userRepository->getAll(['limit' => 1]);

        Cache::put('users', $response);

        return response()->json([
            'message' => 'all systems are a go',
            'users' => $response,
        ]);
    }
}
