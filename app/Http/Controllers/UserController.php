<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $this->authorize('viewAny', User::class);

        $users = User::when(Auth::user()->role_id !== "1", function ($query) {
            return $query->where('id', '=', Auth::user()->id);
        })->orderBy('created_at','desc')->paginate(20);

        return UserResource::collection($users)->preserveQuery();
    }

    public function users()
    {
        //
        // $this->authorize('viewAny', User::class);
        $users = User::when(Auth::user()->role_id !== "1", function ($query) {
            return $query->where('id', '=', Auth::user()->id);
        })->orderBy('created_at','asc')->get();

        return response(['users' => UserResource::collection($users)]);
    }

    public function search(Request $request)
    {
        //
        // $this->authorize('viewAny', User::class);
        $input=$request->all();

        $users = User::when(Auth::user()->role_id !== "1", function ($query) {
            return $query->where('id', '=', Auth::user()->id);
        })->where('name','LIKE','%'.$input['searchTerm'].'%')
            ->orderBy('created_at','asc')
            ->paginate(20);

        return UserResource::collection($users)->preserveQuery();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // $this->authorize('create', User::class);
        $input=$request->all();
        $validator = Validator::make($input, [
            'name'=>'required',
         ]);
        if ($validator->fails()) {
            return response(['error' => $validator->errors()]);
          }


        $input['role_id'] = 2;
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);

        $role = $user->role;

        if (isset($role)) {
            $user->assignRole($role->name);
        }
        $permissions = [];
        foreach ($user->role->permissions as $permission) {
            $permissions[] = $permission->name;
        }
        $user->syncPermissions($permissions);


        return response()->json([
            'user' => new UserResource($user)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
        // $this->authorize('view', $user);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //

        // $this->authorize('update', $user);
        $input=$request->all();
        $validator = Validator::make($input, [
            'name'=>'required',
         ]);
        if ($validator->fails()) {
            return response(['error' => $validator->errors()]);
          }
        $user->update($input);
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
        // $this->authorize('delete', $user);
        $user->delete();
        return response(['message' => 'User was deleted successfully']);
    }
}