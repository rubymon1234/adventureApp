<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class apiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $header = $request->header('Authorization');
        if($header == null) {
            $data = [];
            $data['errorCode'] = 401;
            $data['data'] = [];
            $data['message'] = "Authorization Token not found";
            $data['hasError'] = true;
            return response()->json($data, 401);
        } else {
            $tokenValid = User::where("api_token", $header)->get();
            if(count($tokenValid) == 0) {
                $data = [];
                $data['errorCode'] = 401;
                $data['data'] = [];
                $data['message'] = "Invalid/Expired token";
                $data['hasError'] = true;
                return response()->json($data, 401);
            }
        }
        return $next($request);
    }
}
