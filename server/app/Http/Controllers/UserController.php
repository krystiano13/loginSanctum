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
}
