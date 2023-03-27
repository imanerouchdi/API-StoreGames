<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

//use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for Products
        Permission::create(['name' => 'add product']);
        Permission::create(['name' => 'edit every product']);
        Permission::create(['name' => 'edit my product']);
        Permission::create(['name' => 'delete every product']);
        Permission::create(['name' => 'delete my product']);

        // Create permissions for Categories
        Permission::create(['name' => 'show category']);
        Permission::create(['name' => 'add category']);
        Permission::create(['name' => 'edit category']);
        Permission::create(['name' => 'delete category']);

        // Create permissions for Roles
        Permission::create(['name' => 'show role']);
        Permission::create(['name' => 'add role']);
        Permission::create(['name' => 'edit role']);
        Permission::create(['name' => 'delete role']);
        Permission::create(['name' => 'assign role']);

        // Create permissions for Profile
        Permission::create(['name' => 'edit my profile']);
        Permission::create(['name' => 'edit every profile']);
        Permission::create(['name' => 'delete my profile']);
        Permission::create(['name' => 'delete every profile']);

        // Create permission for assigning permissions to roles
        Permission::create(['name' => 'assign permission']);

        Role::create(['name' => 'admin'])
            ->givePermissionTo(Permission::all());

        Role::create(['name' => 'seller'])
            ->givePermissionTo(
                'add product',
                'edit my product',
                'delete my product',
                'edit my profile',
                'delete my profile'
            );

        Role::create(['name' => 'user'])
            ->givePermissionTo(
                'edit my profile',
                'delete my profile'
            );
    }
}
