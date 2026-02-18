<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage-products',
            'manage-categories',
            'manage-orders',
            'view-orders',
            'update-order-status',
            'manage-stock',
            'view-stock',
            'adjust-stock',
            'manage-promos',
            'manage-reports',
            'manage-settings',
            'manage-users',
            'manage-pages',
            'manage-email-templates',
            'manage-payment-gateways',
            'view-products',
            'view-customers',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Super Admin - gets all permissions via Gate::before
        $superAdmin = Role::create(['name' => 'super-admin']);

        // Admin
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'manage-products', 'manage-categories', 'manage-orders', 'view-orders',
            'update-order-status', 'manage-stock', 'view-stock', 'adjust-stock',
            'manage-promos', 'manage-reports', 'manage-settings', 'manage-pages',
            'manage-email-templates', 'view-products', 'view-customers',
        ]);

        // Warehouse Staff
        $warehouse = Role::create(['name' => 'warehouse-staff']);
        $warehouse->givePermissionTo([
            'view-stock', 'adjust-stock', 'manage-stock', 'view-orders', 'view-products',
        ]);

        // Customer Service
        $cs = Role::create(['name' => 'customer-service']);
        $cs->givePermissionTo([
            'view-orders', 'update-order-status', 'view-products', 'view-customers',
        ]);

        // Customer (no admin permissions)
        Role::create(['name' => 'customer']);
    }
}
