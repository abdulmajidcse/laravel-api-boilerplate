<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Events\Lockout;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\RateLimiter;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        // check login attempt
        if (RateLimiter::tooManyAttempts($request->throttleKey(), 5)) {
            event(new Lockout($request));

            $seconds = RateLimiter::availableIn($request->throttleKey());

            return response()->json([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ], 422);
        }

        // check user data
        $credentials = $request->validated();
        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            RateLimiter::clear($request->throttleKey());
            $token = $user->createToken('api');
            return response()->json(['token' => $token->plainTextToken]);
        }

        RateLimiter::hit($request->throttleKey());

        return response()->json(['email' => __('auth.failed')], 422);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['logout' => true]);
    }
}
