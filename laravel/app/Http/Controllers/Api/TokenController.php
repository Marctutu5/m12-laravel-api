<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\Role;
use App\Http\Resources\UserResource;

class TokenController extends Controller
{
    public function user(Request $request) 
    {
        $user = User::where('email', $request->user()->email)->first();

        return response()->json([
            "success" => true,
            "user"    => new UserResource($user),
            "roles"   => [ $user->role->name ],
        ]);
    }

    public function register(Request $request) 
    {
        $validatedData = $request->validate([
            "name"      => "required|string|max:255",
            "email"     => "required|string|email|max:255|unique:users",
            "password"  => "required|string|min:8"
        ]);
        
        $user = User::create([
            "name"      => $validatedData["name"],
            "email"     => $validatedData["email"],
            "password"  => Hash::make($validatedData["password"]),
            "role_id"   => Role::AUTHOR,
        ]);

        // event(new \Illuminate\Auth\Events\Registered($user));
        
        // $user->sendEmailVerificationNotification();

        // Crear wallet para el nuevo usuario con un saldo inicial de 1000 coins
        $user->wallet()->create([
            'coins' => 1000
        ]);

        return $this->_generateTokenResponse($user);
    }

    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ]);
 
        if (Auth::attempt($credentials)) {
            // Get user
            $user = User::where([
                ["email", "=", $credentials["email"]]
            ])->firstOrFail();
            // Revoke all old tokens
            $user->tokens()->delete();
            // Generate new token
            return $this->_generateTokenResponse($user); 
        } else {
            return response()->json([
                "success" => false,
                "message" => "Invalid login credentials"
            ], 401);
        }
    }

    public function logout(Request $request) 
    {
        // Revoke token used to authenticate current request...
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            "success" => true,
            "message" => "Current token revoked",
        ]);
    }

    protected function _generateTokenResponse(User $user)
    {
        $token = $user->createToken("authToken")->plainTextToken;

        return response()->json([
            "success"   => true,
            "authToken" => $token,
            "tokenType" => "Bearer"
        ], 200);
    }
}