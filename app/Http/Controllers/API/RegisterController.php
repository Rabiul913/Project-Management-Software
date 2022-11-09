<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Str;
class RegisterController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        // $input = $request->all();
        // return $input;
        $input['password'] = Hash::make($request->password);
        $input['remember_token'] = Str::random(10);
        $user = User::create($input);
        $success['token'] =  $user->createToken($user->email)->accessToken;
        $success['name'] =  $user->name;
   
        return $this->sendResponse($success, 'User register successfully.');
    }
    // public function register(Request $request)
    // {
    //     $this->validate($request,[
    //         'name'=>'required',
    //         'email'=>'required|email|unique:users',
    //         'password'=>'required|min:8',
    //     ]);
        
    //     $user= User::create([
    //         'name' =>$request->name,
    //         'email'=>$request->email,
    //         'password'=>Hash::make($request->password)
    //     ]);
    //     return $user;

    //     $success['success'] =  true;
    //     $success['name'] =  $user->name;        
    //     $success['token'] =  $user->createToken($request->email)->accessToken;
    //     //return the access token we generated in the above step
    //     return response()->json(['token'=>$success],200);
              
    // }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            

            return $user;
            $success['token'] =  $user->createToken($user->email)-> accessToken;

            // $success['access_token'] =$user->accessToken;
            $success['name'] =  $user->name;
            $success['email']= $user->email;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }
}
