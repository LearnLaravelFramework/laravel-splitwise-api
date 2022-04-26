<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{

    public function get_token(Request $request){
        //dd($request->headers);
        $user = User::where('email', $request->header('email'))->first();
        $token = PersonalAccessToken::where('tokenable_id',$user->id)->first();
        //$token = request()->user()->currentAccessToken()->token;

        if( Hash::check( $request->header('password'),$user->password  ) ){
            return response()->json(['data' => $token->token], 400);

        }
        else{
            return response()->json(['data' => 'bad_request'], 400);
        }
    }
}
