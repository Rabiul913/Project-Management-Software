<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;

class PassportAuthController extends Controller
{
    //

    public function registerUser(Request $request){
   
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8',
        ]);
        $user= User::create([
            'name' =>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password)
        ]);
        $success['success'] =  true;
        $success['name'] =  $user->name;
        $success['token'] =  $user->createToken('employeattendancemanager')->accessToken;
        //return the access token we generated in the above step
        return response()->json(['token'=>$success],200);
    }

    /**
     * login user to our application
     */
    public function loginUser(Request $request){
        $login_credentials=[
            'email'=>$request->email,
            'password'=>$request->password,
        ];
        if(auth()->attempt($login_credentials)){
            $success['success'] =  true;
            $success['token'] =  auth()->user()->createToken('employeattendancemanager')->accessToken;
            //now return this token on success login attempt
            return response()->json(['token' => $success], 200);
        }
        else{
            //wrong login credentials, return, user not authorised to our system, return error code 401
            $success['success'] =  false;
            $success['message'] =  "UnAuthorised Access";
            return response()->json(['error' => $success], 401);
        }
    }

    public function logoutUser(){
        if (Auth::check()) {
            $user = Auth::user()->token();
            $user->revoke();
            return response()->json(['success' => "Logout Successfully"], 200);
        }
    }

    /**
     * This method returns authenticated user details
     */
    public function authenticatedUserDetails(){
        //returns details
        return response()->json(['authenticated-user' => auth()->user()], 200);
    }
}
