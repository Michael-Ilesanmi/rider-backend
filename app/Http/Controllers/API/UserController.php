<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function register(UserRequest $request) : JsonResponse
    {
        $user = $request->all();
        $user['password'] = bcrypt($user['password']);
        User::updateOrCreate(['email'=>$user['email']], $user);
        $token = auth()->attempt($request->safe()->only(['email', 'password']));
        $user = User::find(auth()->user()->id);
        return ResponseController::response(true, ['token'=>$token, 'user'=>$user], Response::HTTP_CREATED);
    }

    public function login(UserRequest $request) : JsonResponse 
    {
        $token = auth()->attempt($request->safe()->only(['email', 'password']));
        if (! $token) {
            return ResponseController::response(false, 'Incorrect credentials', Response::HTTP_BAD_REQUEST);
        }
        $user = User::find(auth()->user()->id);
        return ResponseController::response(true, ['token'=>$token, 'user'=>$user], Response::HTTP_CREATED);
    }
    public function auth() : JsonResponse 
    {
        $user = User::find(auth()->user()->id);
        return ResponseController::response(true, $user, Response::HTTP_CREATED);
    }
}
