<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registrationData = $request->all();
        $validate = Validator::make($registrationData, [
            'name' => 'required|max:60',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); 
        
        $registrationData['password'] = bcrypt($request->password);
        $user = User::create($registrationData);
        return response([
            'message' => 'Register Success',
            'user' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); 
        
        if(!Auth::attempt($loginData))
            return response(['message' => 'Invalid Credentials'], 401);

        $user = Auth::user();
        $token = $user->createToken('Authentication Token')->accessToken;

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);
    }

    public function show(){
        $user = User::find(Auth::id());

        if(!is_null($user)){
            return response([
                'message' => 'Retrieve User Success!',
                'user' => $user
            ], 200);
        }

        return response([
            'message' => 'User Not Found!',
            'user' => null
        ], 404);
    }


    public function update(Request $request){
        $user = user::find(Auth::id());

        if(is_null($user)){
            return response([
                'message' => 'User Not Found!',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'name' => 'required|max:60',
            'email' => ['required', 'email:rfc,dns', Rule::unique('users')->ignore($user)],
            'password' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $user->name = $updateData['name'];
        $user->email = $updateData['email'];
        $user->password = bcrypt($updateData['password']);


        if($user->save()){
            return response([
                'message' => 'Update User Success!',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'Update User Failed!',
            'data' => null
        ], 400);
    }

    // public function logout(Request $request){
    //     $request->user()->token()->revoke();
    //     return response(['message' => 'Succesfully logged out']);
    // }
}
