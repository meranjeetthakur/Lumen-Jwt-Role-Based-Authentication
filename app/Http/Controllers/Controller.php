<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\View;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
   
    public $authUser;
    public function __constructor()
    {
        $authUser = app()->auth->user();
        View::share('authUser', $authUser);
    }
    public function get_roles()
    {
        $roles = app()->auth->user()->roleuser;
        $userRoles = [];
        foreach($roles as $role)
        {
            $userRoles[] = $role->role->name;
        }
       return $userRoles;
    }
    
    public function hasRole($role)
    {
        $userRoles = $this->get_roles();
        
        $hasRole = in_array($role, $userRoles) ? 1 : 0;
        
        return $hasRole;
    }
}
