<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        \Log::info($request);
        info('$this->auth->guard($guard)->guest() '.$this->auth->guard($guard)->guest());
        return $next($request);
        if ($this->auth->guard($guard)->guest()) {
          
            // return response()->json(['status' => 'error', 'message' => 'Token invalid','data'=>'','error_code'=>'401']);
            return response()->json([
                'status' => 'error',
                'message' => 'Token invalid',
                'data' => '',
                'error_code' => '401'
            ], 401);
            
        }

        return $next($request);
    }
}
