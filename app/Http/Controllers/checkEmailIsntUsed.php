<?php

namespace App\Http\Controllers;

use App\Http\traits\responseTrait;
use Illuminate\Http\Request;
use App\Models\User;

class checkEmailIsntUsed extends Controller
{
    use responseTrait;
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'email'=>'required'
        ]);
        return $this->successResponse([
            "emailAlreadyUsed"=>!!User::where('email',$request['email'])->first()
        ]);
    }
}
