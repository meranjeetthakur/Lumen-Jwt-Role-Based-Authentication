<?php

namespace App\Http\Middleware;
use Illuminate\Contracts\Auth\Factory as Auth;
use Closure;

class RoleMiddleware
{
     /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $userRoles;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
//        $this->role = $auth->user()->role;
        $this->userRoles = $auth->user()->roleuser;
    }

    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        $roleAssigned = explode('-', $roles);
        $isAllowed = 0;
        foreach($this->userRoles as $role)
        {
           $isAllowed =  in_array($role->role->name, $roleAssigned) ? 1 : 0;
        }
        
        if($isAllowed)
        {
            return $next($request);
        }
        else 
        {
             return response('Unauthorized access', 401);
        }
        
        
    }
}
