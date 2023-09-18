<?php

namespace App\Http\Middleware;

use App\Http\traits\responseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class activeAccount
{
    use responseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::user()->email_verified_at!=null)
        return $next($request);
        else
            return $this->errorResponse(statusCode: 400,message: "please verify your account to continue");
    }
}
