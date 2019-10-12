<?php
namespace App\Http\Controllers;
use App\Models\Role;

class RolesController extends Controller
{
    public function getRole()
    {
        $roles = Role::all();
        
        if ($roles)
        {
            $res['status'] = true;
            $res['data'] = $roles;

            return response($res);
        }
    }
}