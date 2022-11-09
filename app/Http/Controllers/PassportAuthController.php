<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;
use Illuminate\Support\Facades\Hash;
use Validator;

class PassportAuthController extends Controller
{
    //

    public function registerUser(Request $request){
        // return $request;
        // dd()
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8',
        ]);
        $user= User::create([
            'name' =>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);
        $success['success'] =  true;
        $success['name'] =  $user->name;
        $success['token'] =  $user->createToken($request->email)->accessToken;
        //return the access token we generated in the above step
        return response()->json(['token'=>$success],200);
    }

    /**
     * login user to our application
     */
    // public function loginUser(Request $request){

    //     // return $request->email;
    //     $login_credentials=[
    //         'email'=>$request->email,
    //         'password'=>$request->password,
    //     ];

    //     // return auth()->attempt($login_credentials);
    //     if(auth()->attempt($login_credentials)){
    //         $success['success'] =  true;
    //         $success['token'] =  auth()->user()->createToken($request->email)->accessToken;
    //         //now return this token on success login attempt
    //         return response()->json(['token' => $success], 200);
    //     }
    //     else{
    //         //wrong login credentials, return, user not authorised to our system, return error code 401
    //         $success['success'] =  false;
    //         $success['message'] =  "UnAuthorised Access";
    //         return response()->json(['error' => $success], 401);
    //     }
    // }

    public function loginUser(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken($request->email)-> accessToken; 
            $success['name'] =  $user->name;
   
            // return $this->sendResponse($success, 'User login successfully.');
            return response()->json(['success' => $success], 200);
        } 
        else{ 
            $success['success'] =  false;
            $success['message'] =  "UnAuthorised Access";
            return response()->json(['error' => $success], 401);
        }
    }


    public function logoutUser(){        
            $user = Auth::user()->token();
            $user->revoke();
            Auth::logout();

            if(!empty($user)){
                $success['seccess']=true;
                $success['message']="Logout Successfully";
            }else{
                $success['seccess']=false;
                $success['message']="Logout failed";
            }
            return response()->json(['logout' => $success], 200);
       
    }

    /**
     * This method returns authenticated user details
     */
    public function authenticatedUserDetails(){
        return 'hi';
        //returns details
        return response()->json(['authenticated-user' => auth()->user()], 200);
    }
}
