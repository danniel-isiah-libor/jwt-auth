<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Rush\JWTAuth\Services\JWTAuth;

class AuthController extends Controller
{
    protected $auth;

    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => [
                'required',
                'string',
                'exists:users,username'
            ],
            'password' => [
                'required',
                'string'
            ],
            'remember_me' => [
                'nullable',
                'boolean'
            ]
        ]);

        $user = User::where('username', $request->username)->first();

        if (!password_verify($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        $this->auth->viaRequest('api', fn () => $user);

        if ($request->remember_me) {
            JWTAuth::refreshTokensExpireIn(env('JWT_REMEMBER_ME_TOKEN_LIFETIME'));
        }

        $token = Auth::user()->createToken();

        return response()->json($token, 200);
    }

    public function logout()
    {
        return Auth::user()->revokeTokens();
    }

    public function verifyAccessToken()
    {
        $user = Auth::user();

        return response()->json($user, 200);
    }

    public function refreshToken(Request $request)
    {
        $this->validate($request, [
            'refresh_token' => [
                'required',
                'string'
            ]
        ]);

        $token = JWTAuth::refreshAccessToken($request->refresh_token);

        if (!$token) return response()->json(['message' => 'Invalid refresh token'], 422);

        return response()->json($token, 200);
    }
}
