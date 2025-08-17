<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('API Token')->accessToken;

            return response()->json(['token' => $token], 200);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }
    // public function login(Request $request)
    // {
    //  if(!empty($request->input('email')) && !empty($request->input('password'))) {

    //     // Get credentials values
    //     $credentials = [
    //         'email' => $request->get('email'),
    //         'password' => $request->get('password'),
    //         'blocked' => 0,
    //     ];
    //     if (auth()->attempt($credentials)) {

    //         $user = User::find(auth()->user()->getAuthIdentifier());
    //         $values = array(
    //             'last_login_at' => date('Y-m-d H:i:s')
    //         );
    //         User::where('id', $user->id)->update($values);
    //         return response()->json([
    //             'message' => 'Data Fetched Successfully',
    //             'data' => $user
    //         ], 200);
    // }else{
    //     return response()->json([
    //         'message' => 'Invalid email and password',
    //         'data' => ''
    //     ], 400);
    // }

    //  }else{
    //     return response()->json([
    //         'message' => 'Please Enter your email and password',
    //         'data' => ''
    //     ], 400);
    //  } 

    // }

}