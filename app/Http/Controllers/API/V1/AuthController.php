<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(RegistrationRequest $request)
    {
        $data    = collect($request->validated())->only(['name', 'email', 'password', 'balance', 'currency_id']);
        $balance = $request->balance ? $request->balance : 0;
        $data    = $data->merge(['balance' => $balance]);
        $user    = User::create($data->all());
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user,
        ], Response::HTTP_OK);
    }

    public function login(LoginRequest $request)
    {
        $data = collect($request->validated())->only(['email', 'password']);
        $token = null;
        try {
            if (!$token = JWTAuth::attempt($data->all())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Email or Password.',
                ], Response::HTTP_UNAUTHORIZED);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not create token.',
            ], 500);
        }
        return $this->createNewToken($token);
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'data' => auth()->user(),
        ], );
    }

    public function profile()
    {
        return response()->json(['success' => true, 'data' => auth()->user()], Response::HTTP_OK);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['success' => true, 'message' => 'Successfully logged out'], Response::HTTP_OK);
    }

    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }
}
