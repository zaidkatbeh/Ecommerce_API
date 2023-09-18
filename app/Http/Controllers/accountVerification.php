<?php

namespace App\Http\Controllers;

use App\Http\traits\responseTrait;
use App\Mail\AccountVerificationEmail;
use App\Models\account_verification_code;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class accountVerification extends Controller
{
    use responseTrait;
    public function sendCode(){
        if(Auth::user()->email_verified_at==null){
            $vaildVerifcationCode=DB::table('account_verification_codes')
                ->where('user_id',Auth::id())
                ->max('created_at');
            if($vaildVerifcationCode&& $vaildVerifcationCode>=Carbon::now()->subMinutes(3))
                return  $this->errorResponse(statusCode: 429,message: "you need to wait till the old verification code expires to send a new one");
            try {
                $verificationCode=$this->generateVerificationCode();
                Mail::to(Auth::user())->send(new AccountVerificationEmail(userName: "zaid", verificationCode: $verificationCode));
                DB::table('account_verification_codes')->insert([
                    'user_id'=>Auth::id(),
                    'token'=>$verificationCode
                ]);
                return $this->successResponse([
                    'success'=>true
                ]);
            }catch (\Exception $exception){
                return $this->errorResponse(statusCode: 502, message: "there was an error while trying to send the account verification email");
            }
        }
        else
            return $this->errorResponse(statusCode: 400,message: "email already verified");
    }
    public function VerifyCode(Request $request){
        $request->validate([
            'verification_code'=>'required'
        ],[
            'verification_code.required'=>'please enter the validation code'
        ]);
        $verificationCodescount=DB::table('account_verification_codes')
            ->where('token',$request['verification_code'])
            ->whereDate('created_at',Carbon::now()->subMinutes(3))
            ->count();
        if($verificationCodescount>0)
        {
            DB::table('users')
                ->where('id',Auth::id())
                ->update([
                    'email_verified_at'=>Carbon::now()
                ]);
            return $this->successResponse();
        }
        else
            return $this->errorResponse(statusCode: 400, message: "verification code in incorrect or expired");

    }

    private function generateVerificationCode()
    {
        $key="1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $verificationCode='';
        for($i=0;$i<5;$i++){
            $verificationCode .= $key[rand(0,strlen($key))];
        }
        $verificationCode.=Auth::user()->name[0];
        return $verificationCode;
    }

}
