<?php

namespace App\Http\Middleware;

use App\Helper\JWT_TOKEN;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('token');
        $result = JWT_TOKEN::verifyToken($token);

        if ($result == "unauthorized") {
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ],401);
        }else{
            $request->headers->set('email',$result);
            return $next($request);
        }


    }
}
