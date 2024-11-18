<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class AuthController extends Controller
{
    //

    public function login()
    {
        $user=User::where('email','=',request('email'))->first();

        
        if ($user && Hash::check(request('password'), $user->password)) {
            $accessToken = $user->createToken('authToken');

            return response()->json([
                'user' => new UserResource($user),
                'token' => $accessToken,
            ]);
        } else {
            return response(['error' => 'Invalid Email or Password']);
        }
    }


    public function reset(Request $request)
    {
       $input = $request->all(); 
         $validator = Validator::make($input, [
           'email'=>'required',
              ]);
        if ($validator->fails()) {
            return response(['error' => $validator->errors()]);
         }


      $user=User::where('email','=',$request->email)->first();

      if (!isset($user)) {
            return response(['error' => ['email'=>['The email address provided was not found in our records. Kindly check for errors.']]]);
         }

      DB::table('password_resets')->insert([
          'email' => $request->email,
          'token' => Str::random(60),
          'created_at' => Carbon::now()
      ]);
      $tokenData = DB::table('password_resets')
          ->where('email','=',$request->email)->first();

      $url = url('/').'/password/reset/'.$tokenData->token .'?email='.urlencode($user->email);

    }

    public function resetSave(Request $request)
    {
      $input = $request->all(); 
         $validator = Validator::make($input, [
           'email'=>'required',
           'password' => 'required|min:6|confirmed',
           'password_confirmation' => 'required|min:6',
           'token'=>'required',
              ]);
        if ($validator->fails()) {
            return response(['error' => $validator->errors()]);
         }
      $user=User::where('email','=',$request->email)->first();
      $tokenData = DB::table('password_resets')->where('token','=',$request->token)->first();
      if (isset($user) && isset($tokenData)) {
        $userD=array();
        $userD['password']=Hash::make($request->password);
        $user->update($userD);
      DB::table('password_resets')->where('email', '=',$user->email)->delete();
      $accessToken = $user->createToken('authToken');
      return response([ 'user' => new UserResource($user), 'token' => $accessToken]);}
      else{
        response(['error' => ['email'=>['The email address provided was not found in our records. Kindly check for errors.']]]);
      }
    }

    public function checkAuth()
    {
        if (Auth::check()) {
            $user=Auth::user();
            return response(['success' => true, 'user' => new UserResource($user)]);
        }
        else{
            return response()->json(['success' => false, 'message' => 'User is not logged in']);
        }
    }

    public function registerUser(Request $request)
    {
        $input = $request->all();
    
        $validator = Validator::make($input, [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'amount' => 'required|numeric',
            'status' => 'required|string',
            'repayment_period' => 'required|integer',
            'monthly_payment' => 'required|numeric',
        ]);
    
        if ($validator->fails()) {
            return response(['error' => $validator->errors()], 422);
        }
    
        if ($request->hasFile('avatar')) {

            $filename = Str::random(32) . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move('images/', $filename);
            $input['avatar'] = $filename; 
        } else {
            $input['avatar'] = 'avatar.jpg'; 
        }
    
        $input['password'] = Hash::make($input['password']);
        $input['role_id'] = 2; 
    
        // Create the user
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'avatar' => $input['avatar'],
            'role_id' => $input['role_id'],
        ]);
    
        // Assign role and permissions
        $role = $user->role;
        if (isset($role)) {
            $user->assignRole($role->name);
        }
    
        $permissions = [];
        if (isset($role)) {
            foreach ($role->permissions as $permission) {
                $permissions[] = $permission->name;
            }
            $user->syncPermissions($permissions);
        }
    
        // Generate access token
        $accessToken = $user->createToken('authToken')->plainTextToken;
    
        
        // Return the response
        return response([
            'user' => new UserResource($user),
            'token' => $accessToken,
        ], 201);
    }
    
    
      
}