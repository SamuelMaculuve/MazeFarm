<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Stock
            'stock.view', 'stock.create', 'stock.edit', 'stock.delete', 'stock.adjust',
            // POS
            'pos.sell', 'pos.sales.view', 'pos.sales.cancel', 'pos.sales.refund',
            // Purchases
            'purchases.view', 'purchases.create', 'purchases.receive', 'purchases.cancel',
            'suppliers.view', 'suppliers.manage',
            // Customers
            'customers.view', 'customers.create', 'customers.edit',
            'customers.credit.view', 'customers.credit.settle',
            // Insurance
            'insurance.view', 'insurance.manage', 'insurance.claims.update',
            // Reports
            'reports.sales', 'reports.stock', 'reports.insurance',
            // Settings
            'settings.manage',
            // Users
            'users.view', 'users.manage', 'roles.manage',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $roles = [
            'Administrador' => $permissions,

            'Gerente' => [
                'stock.view', 'stock.create', 'stock.edit', 'stock.adjust',
                'pos.sell', 'pos.sales.view', 'pos.sales.cancel', 'pos.sales.refund',
                'purchases.view', 'purchases.create', 'purchases.receive', 'purchases.cancel',
                'suppliers.view', 'suppliers.manage',
                'customers.view', 'customers.create', 'customers.edit',
                'customers.credit.view', 'customers.credit.settle',
                'insurance.view', 'insurance.manage', 'insurance.claims.update',
                'reports.sales', 'reports.stock', 'reports.insurance',
                'users.view',
            ],

            'Farmacêutico' => [
                'stock.view', 'stock.create', 'stock.edit', 'stock.adjust',
                'pos.sell', 'pos.sales.view',
                'purchases.view', 'purchases.receive',
                'customers.view', 'customers.create', 'customers.edit',
                'customers.credit.view',
                'insurance.view', 'insurance.claims.update',
                'reports.sales', 'reports.stock',
            ],

            'Técnico de Farmácia' => [
                'stock.view', 'stock.create',
                'pos.sell', 'pos.sales.view',
                'customers.view',
                'insurance.view',
                'reports.sales',
            ],

            'Caixa' => [
                'pos.sell', 'pos.sales.view',
                'customers.view',
                'insurance.view',
                'reports.sales',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
        }
    }
}
