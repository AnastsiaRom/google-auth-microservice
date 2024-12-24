<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;

use Laravel\Socialite\Facades\Socialite;

use Illuminate\Http\{
    Request,
    JsonResponse,
};

use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function redirectToGoogle(): JsonResponse
    {
        return response()->json([
            'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    public function handleGoogleCallback(Request $request): JsonResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = $this->userService->findByGoogleId($googleUser->id);

        if (!$user) {
            $user = $this->userService->findByEmail($googleUser->email);

            if (!$user) {
                $user = $this->userService->createUser([
                    'name'     => $googleUser->name,
                    'email'    => $googleUser->email,
                    'password' => bcrypt(Str::random(16)),
                ]);
            }

            $this->userService->updateGoogleId($user, $googleUser->id);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ]);
    }
}
