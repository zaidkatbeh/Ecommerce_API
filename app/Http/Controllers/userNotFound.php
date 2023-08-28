<?php

namespace App\Http\Controllers;

use App\Http\traits\responseTrait;
use Illuminate\Http\Request;

class userNotFound extends Controller
{
    use responseTrait;
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return $this->errorResponse(statusCode: 401, message: "please login");
    }
}
