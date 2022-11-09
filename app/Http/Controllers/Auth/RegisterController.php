<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    //


    public function user_register(Request $request){
        
        $this->validate($request, [
            'username'=>'required',
            'email'=>'required|email',
            'password' => 'required|same:confirm_password|min:8',           
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('APP_URL') . '/api/v1/register',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('name' => $request->username, 'email'=>$request->email, 'password'=>$request->password),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $register = json_decode($response);

        dd($register);
        // return redirect('/');
    }
}
