<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Role extends Model
{
    
     public function Role()
    {
        return $this->hasMany('App\Models\RoleUser', 'role_id', 'id');
    }
}