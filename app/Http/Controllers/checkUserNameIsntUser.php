<?php

namespace App\Http\Controllers;

use App\Http\traits\responseTrait;
use Illuminate\Http\Request;
use App\Models\User;

class checkUserNameIsntUser extends Controller
{
    /**
     * Handle the incoming request.
     */
    use responseTrait;
    public function __invoke(Request $request)
    {
        $request->validate([
            'name'=>'required'
        ]);
        return $this->successResponse(['userNameAlreadyUsed'=>!!User::where('name',$request['name'])->first()]);
    }
}
