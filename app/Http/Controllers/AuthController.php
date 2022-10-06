<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
       $rules = [
        'name' => 'required|string',
        'email'=> 'required|email|unique:users',
        'password'=> 'required|string|min:6',
       ];
       $validator = Validator::make($request ->all(),$rules);

       if($validator->fails()){
         return response()->json($validator->errors(), 400);
       }

       $user = User::create([
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>Hash::make($request->password),

       ]);

       $token = $user->createToken('Personal Access Token')->plainTextToken;
       $response =['user'=>$user,'token'=>$token];
       return response()->json($response, 200);

    }

    public function login(Request $request)
    {
      $rules = [
        'email'=> 'required',
        'password'=> 'required|string',
       ];
        $request ->validate($rules);

        $user = User::where('email',$request->email)->first();
        if($user && Hash::check($request->password,$user->password)){
          $token = $user->createToken('Personal Access Token')->plainTextToken;
          $response=['user'=>$user,'token'=>$token];
          return response()->json($response, 200);

        }

        $response = ['message' =>'incorrect email or password'];
        return response()->json($response, 400);
    }

    public function getmessages(){
        $messages = Message::all();
        $response =['messages'=>$messages];
        return response()->json($response, 200);
    }

    public function sendmessage(Request $request){
        $user_id = Auth::user()->id;
        $user = Message::create([
            'user_id'=>$user_id,
            'message_text'=>$request->message_text,
           ]);
        $response =['messages'=>'message send successfully'];
        return response()->json($response, 200);
    }

}
