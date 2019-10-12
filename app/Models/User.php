<?php

namespace App\Models;
 
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;
 
class User extends Model implements JWTSubject, AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
 
    protected $fillable = [
        'username', 'email', 'password', 'api_token', 'role'
    ];
 
    protected $hidden = [
        'password','api_token'
    ];
    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    
   
    public function roles()
    {
        return $this->belongsToMany('\App\Models\Role');
    }
    
    public function RoleUser()
    {
        return $this->hasMany('App\Models\RoleUser', 'user_id', 'id');
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