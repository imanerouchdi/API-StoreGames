<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function assignPermissionToRole(Request $request, $role)
    {
        $permissions = $request->permissions;
        $role = Role::where('name', $role)->firstOrFail();

        $role->syncPermissions($permissions);

        return response()->json([
            'status' => true,
            'message' => 'Permissions assigned successfully!',
        ]);

    }

    public function removePermissionFromRole(Request $request, $roleName)
    {

        $permissions = $request->permissions;

        $role = Role::where('name', $roleName)->firstOrFail();

        foreach ($permissions as $permission) {
            $role->revokePermissionTo($permission);
        }

        return response()->json([
            'status' => true,
            'message' => 'Permissions removed successfully!',
        ]);
    }
}
