<?php

namespace App\Http\Controllers;

use App\Models\Token;
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
            $token = auth() -> user() -> createToken(auth() -> user() -> name, expiresAt:now() -> addHour());

            return response() -> json([
                'status' => true,
                'token' => $token -> accessToken -> id,
                'user' => auth() -> user() -> name,
            ],200);
        }
    }

   public function checkToken(Request $request) {
        if($request -> get('token') && $request -> get('name')) {
            $token = Token::where('id', $request -> get('token'));

            if($token -> count() > 0 && 
            (strtotime(now()) - strtotime($token -> get('expires_at') -> first() -> expires_at)) < 0
            ) {
                return response() -> json([
                    'status' => true,
                    'token' => $request -> get('token'),
                    'name' => $request -> get('name'),
                ], 200);
            }

            else {
                return response() -> json([
                    'status' => false,
                    'message' => 'token has expired',
                ], 200);
            }

        }
   }
}
