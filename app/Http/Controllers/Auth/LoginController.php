<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Session;
use DB;

class LoginController extends Controller
{
    //
    public function adminLogin(Request $request){
        // dd($request);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('APP_URL') . '/api/v1/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('email'=>$request->email, 'password'=>$request->password),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $login = json_decode($response);

        $user_id = DB::table('oauth_access_tokens')
        ->where('name', $login->data->email)
        ->first();
        // Session::put('access_token', $login->data->token);
        dd($user_id);
        return view('home');
    }

    public function logout()
    {
        // dd('hi');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('APP_URL') . '/api/v1/logout',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            // CURLOPT_POSTFIELDS => array('token'=>$request->token),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $logout = json_decode($response);

        dd($logout);
        return redirect('/');
    }
}
