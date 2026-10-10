<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreRequest;
use App\Models\User;
use App\Enums\Role;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(StoreRequest $request){
        $validated=$request->validated();

        $password=Hash::make($validated['password']);

        $user = new User([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $password,
        ]);
        $user->role = Role::USER;
        $user->save();

        return response()->json([
            'message'=>__("user.registered_successfully"),
            'user'  => new UserResource($user),
            'token' => $user->createToken('api')->plainTextToken,
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password) || $user->isAdmin()) {
            throw ValidationException::withMessages(['email' => [__('auth.failed')]]);
        }

        return response()->json([
            'message'=>__("user.logged_in_successfully"),
            'user'  => new UserResource($user),
            'token' => $user->createToken('api')->plainTextToken,
        ],201);
    }

    public function me(Request $request)
    {
        return response()->json(
            [
                'message'=>__("user.user_data_returned_successfully"),
                'user'=>new UserResource($request->user())
            ],200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message'=>__("user.logged_out_successfully")],200);
    }
}
