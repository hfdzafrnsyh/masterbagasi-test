<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    //



    public function login(Request $request){
        
        $user = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);


        if(!Auth::attempt($user)){
            return response()->json([
                'status' => 'error',
                'message' => 'Users not authenticate'
            ],404);
        }


    
        // $token = Auth::user()->createToken("authToken")->accessToken;
       $token = Auth::user()->createToken('auth-token', ['server:update'])->plainTextToken;

        return response()->json([
            'user' => Auth::user(),
            'token' => $token
        ],200);
    }



    public function register(Request $request){

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
            'confirmed_password' => 'required|same:password'
        ]);


        $user = User::where('email' , $request->email)->first();

        if($user){
            return response()->json([
                'status' => 'error',
                'message' => 'Email has Registered'
            ],400);
        }

  
        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'users'
        ]);

        $token = $newUser->createToken('auth-token', ['server:update'])->plainTextToken;

        return response()->json([
            'user' => $newUser,
            'token' => $token
        ]);


    }


    public function logout(){

        try{

          Auth::user()->currentAccessToken()->delete();
        
         return response()->json([
            'message' => 'Logout success'
         ],200);    
        
        }catch(Exception $e){
            return response()->json([
                'error' => 'Internal Server Error'
             ],500);    
                
        }
    }


}
