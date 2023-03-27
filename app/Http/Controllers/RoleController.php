<?php

namespace App\Http\Controllers;

use App\Models\Product;
//use http\Client\Curl\User;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\jsonResponse
     */
    public function index()
    {
        $roles = Role::select('name')->get();
        return response()->json([
            'status' => 'success',
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\jsonResponse
     */
    public function store(Request $request)
    {
        $role = Role::create($request->all());
        return response()->json([
            'status' => true,
            'message' => "'{$role->name}' role added successfully!",
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\jsonResponse
     */
    public function show($id)
    {
        $role = Role::find($id);
        if(!$role){
            return response()->json(['message' => 'Sorry, this role doesn\'t exist!']);
        }
        return response()->json([
            'role' => $role->name
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\jsonResponse
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        $oldRole = $role->name;

        $role->update($request->all());
        return response()->json([
            'status' => true,
            'role' => "({$oldRole}) was edited to ({$role->name})"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\jsonResponse
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        $oldRole = $role->name;
        $role->delete();
        return response()->json([
            'message' => "({$oldRole}) was deleted!"
        ]);
    }

    public function assignRole(Request $request, $id)
    {
        $user = User::find($id);
        if(!$user)
        {
            return response()->json(['message' => 'This user doesn\'t exist!']);
        }

        $user->syncRoles([$request->name]);

        return response()->json([
            'status' => true,
            'message' => 'Role assigned successfully!',
        ]);
    }

    public function removeRole(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['Message' => "This user doesn't exist!"]);
        }

        $roleName = $request->name;

        if(!$user->hasRole($roleName)){
            return response()->json(['Message' => "This user doesn't have ({$roleName}) role!"]);
        }

        $user->removeRole($roleName);

        return response()->json([
            '=======' => "==================== Remove Role ====================",
            'Message' => 'Role removed successfully!',
        ]);
    }


}
