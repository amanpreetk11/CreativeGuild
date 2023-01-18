<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Albums;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;


class UserController extends Controller
{
    public function register(Request $request){

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric|min:10',
            'bio' => 'required',
            'password' => 'required|confirmed',
        ]);

        // if($validator->fails()){
        //     return response()->json($validator->errors());
        // }
        
        $jsonString = file_get_contents(base_path('storage/landscapes.json'));
        $data = json_decode($jsonString);
        $profile_picture = $data->profile_picture;

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'bio' => $request->get('bio'),
            'password' => Hash::make($request->get('password')),
            'profile_picture' => '/'.$profile_picture
        ]);

        $albums = $data->album;

        foreach($albums as $key => $val){
            $album = Albums::create([
                'user_id' => $user->id,
                'title' => $val->title,
                'description' => $val->description,
                'img' => $val->img,
                'date' => $val->date,
                'featured' => $val->featured,
            ]);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'), 201);

    }

    public function login(Request $request){

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            if(!$token = JWTAuth::attempt($request->all())){
                return response()->json(['error'=>'Invalid Credentials!'], 400);
            }
        }catch (JWTException $e){
            return response()->json(['error'=>'Token could not be created!'], 500);
        }

        return response()->json(compact('token'));

    }

    public function profile(){

        try{
            if(!$user = JWTAuth::parseToken()->authenticate()){
                return response()->json(['User Not Found!'], 400);
            }
        }catch (TokenExpiredException $e){
            return response()->json(['Expired Token!'], $e->getStatusCode());
        }catch (TokenInvalidException $e){
            return response()->json(['Invalid Token!'], $e->getStatusCode());
        }catch (JWTException $e){
            return response()->json(['Token Absent!'], $e->getStatusCode());
        }

        return response()->json(['user' => $user, 'albums' => $user->albums]);
    }

    public function checkToken(){
        return response()->json(['success' => true],200);
    }

    public function logout(){
        $logout = auth()->logout();
        return response()->json(['success' => true],200);
    }
}