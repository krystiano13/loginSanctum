<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function register(Request $request) {
        $fields = $request -> all();

        $validator = Validator::make($fields,[
            'name' => ['required', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed']
        ]);

        if($validator -> fails()) {
            return response() -> json([
                'status' => false,
                'message' => "validation error",
                'errors' => $validator -> errors()
            ], 401);
        }

        User::create($fields);

        return response() -> json([
            'status' => true,
            'message' => 'successfully created an account',
        ], 200);
    }

    public function login(Request $request) {
        $fields = $request -> all();

        $validator = Validator::make($fields,[
            'name' => ['required'],
            'password' => ['required']
        ]);

        if($validator -> fails()) {
            return response() -> json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator -> errors()
            ],401);
        }

        if(!auth() -> attempt(['name' => $fields['name'], 'password' => $fields['password']])) {
            return response() -> json([
                'status' => false,
                'message' => 'Wrong username or password'
            ],401);
        }

        else {
            $token = auth() -> user() -> createToken('user_token',expiresAt:now() -> addDay());

            return response() -> json([
                'status' => true,
                'token' => $token -> plainTextToken
            ],200);
        }
    }
}
