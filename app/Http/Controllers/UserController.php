<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Role;
use DB;

class UserController extends Controller
{
    protected $token;
    
    public function __constructor()
    {
        $this->middleware('auth', ['except' =>['register']]);
    }
    public function register(Request $request)
    {
        $rules = [
            'username' => 'required',
            'email' => 'required',
            'password' => 'required',
         ];
 
        $customMessages = [
             'required' => 'Please fill attribute :attribute'
        ];
        
        $this->validate($request, $rules, $customMessages);
 
        try
        {
            $hasher = app()->make('hash');
            $username = $request->input('username');
            $email = $request->input('email');
            $password = $hasher->make($request->input('password'));
 
            $record = User::create([
                'username'=> $username,
                'email'=> $email,
                'password'=> $password,
                'api_token'=> $hasher->make($request->input('password'))
            ]);
            $role = Role::where('name', 'member')->first();
            
            //attach role to newly created user
            DB::table('role_user')->insert(['user_id' => $record->id, 'role_id' => $role->id]);
            $res['status'] = true;
            $res['message'] = 'Registration success!';
            return response($res, 200);
        } 
        catch (\Illuminate\Database\QueryException $ex) 
        {
            $res['status'] = false;
            $res['message'] = $ex->getMessage();
            return response($res, 500);
        }
    }
 
    public function get_user()
    {
        $user = User::all();
        
        if($user)
        {
            $res['status'] = true;
            $res['data'] = $user;

            return response($res);
        }
        else
        {
            $res['status'] = false;
            $res['message'] = 'Cannot find user!';

            return response($res);
        }
    }
    
    public function update_user($id)
    {
        $user = User::where('id', $id)->first();
//        $allowed = false;
        
        if($user && Gate::allows('update-user', $user))
        {
            $user->firstname = $request->input('username');
            $user->save();
            
            $res['status'] = true;
            $res['message'] = "User updated successfully.";
            
            return response($res);
        }
        else
        {
            $res['status'] = false;
            $res['message'] = "No user found.";
            
            return response($res);
        }
    }
    
    public function delete_user($id)
    {
        $user = User::where('id', $id)->first();
        
        
        if($user)
        {
            $user->delete();
            $res['status'] = true;
            $res['message'] = "User deleted successfully.";
            
            return response($res);
        }
        else
        {
            $res['status'] = true;
            $res['message'] = "No user found.";
            
            return response($res);
        }
    }
//   public function privateAction()
//    {
//        if (Gate::allows('admin-only', auth()->user())) {
//            return view('private');
//        }
//        return 'You are not admin!!!!';
//    } 
    
    
//    public function edit($id)
//    {
//        $user = User::find($id);
//        $allowed = false;
//        if($user && Gate::allows('update-user', $user))
//        {
//            $allowed = true;
//        }
//        else {
//            $user = [];
//        }
//        
//        return response(['message'=> $user, 'allowed'=> $allowed]);
//    }
    
}