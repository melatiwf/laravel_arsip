<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{    
    public function register(Request $request)
    {        
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password'
        ]);

        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Ada Kesalahan',
                'data' => $validator->errors()
            ]);
        }        
        
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['name'] = $user->name;

        return response()->json([
            'success' => true,
            'message' => 'Sukses register',
            'data' => $success
        ]);
    }

    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user= User::where('email', $request->email)->first();
        
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'success'   => false,
                    'message' => ['These credentials do not match our records.']
                ], 404);
            }
        
            $token = $user->createToken('ApiToken')->plainTextToken;
        
            $response = [
                'success'   => true,
                'user'      => $user,
                'token'     => $token
            ];
        
        return response($response, 201);
    }
    
    public function logout()
    {
        auth()->logout();
        return response()->json([
            'success'    => true
        ], 200);
    }

}
