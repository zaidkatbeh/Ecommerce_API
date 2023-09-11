<?php

namespace App\Http\Controllers;

use App\Http\traits\responseTrait;
use App\Models\User as UserModel;
use App\Http\Resources\user as UserResource;
use App\Http\Requests\register as RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class user extends Controller
{
    use responseTrait;
    public function register(RegisterRequest $request){
        $user=new UserModel();
        $user->name=$request['userName'];
        $user->email=$request['email'];
         try{
            $image=$request->file('profile_picture');
            $newImageName='/user'.explode(' ',$user->name)[0].'.'.$image->getClientOriginalExtension();
            $image->move('images/profile_pictures',$newImageName);
            $user->profile_picture=$newImageName;
            $encreptedPassword=Hash::make($request['password']);
            $user->password=$encreptedPassword;
            $user->save();
            $token=null;
            if(Auth::attempt(['email'=>$user->email,'password'=>$request['password']]))
                $token=$user->createToken('AuthToken');
            $user->token=$token;
            return $this->successResponse([
                'user'=>new UserResource($user)
            ]);
        }catch (\Exception $exception){
            return $this->errorResponse(message: 'an error accorded while trying to move the image');
        }

    }

    public function login(Request $request){
        $request->validate([
           'email'=>'required|email',
           'password'=>'required|string|min:8,regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/'
        ],[
            'password.regex'=>'the password must contain at least one uppercase char,one lowercase chart and a number',
            'email.exists'=>'wrong cardinalities',
            ]);

        if (Auth::attempt(['email'=>$request['email'],'password'=>$request['password']],$request['remember_me']))
        {
            Auth::user()->token=Auth::user()->createToken('AuthToken');
            if($request['remember_me'])
            {
                return $this->successResponse(['user'=>new UserResource(Auth::user()),'remember_me_token'=>Auth::user()->getRememberToken()]);
            }
            else
                return $this->successResponse(['user'=>new UserResource(Auth::user())]);
        }
        else
            return $this->errorResponse(statusCode: 400, message: "wrong cardinalities");
    }
    public function loginUsingRememberToken(Request $request){
        $request->validate([
            'userID'=>'required|exists:users,id',
            'remember_token'=>'required|string'
        ]);
        $user=UserModel::find($request['userID']);
        if($user&& hash_equals($user->getRememberToken(),$request['remember_token']))
        {
            $user->token=$user->createToken('AuthToken');
            return $this->successResponse(['user'=>new UserResource($user),'remember_me_token'=>$user->getRememberToken()]);

        }
        else
            return $this->errorResponse(statusCode: 400, message: "wrong cardinalities");
    }
    public function logOut(Request $request){
        $loggedOut=Auth::user()->currentAccessToken()->delete();
        return $this->successResponse(['loggedOut'=>$loggedOut]);
    }

}
