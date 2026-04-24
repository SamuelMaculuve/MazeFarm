<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Branch;
use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(RolesPermissionsSeeder::class);

        $branch = Branch::firstOrCreate(
            ['name' => 'Sede'],
            ['is_active' => true, 'is_main' => true]
        );

        $categories = [
            ['name' => 'Medicamentos Gerais', 'description' => 'Medicamentos de uso geral'],
            ['name' => 'Antibióticos', 'description' => 'Antibióticos e antimicrobianos'],
            ['name' => 'Analgésicos', 'description' => 'Analgésicos e anti-inflamatórios'],
            ['name' => 'Vitaminas e Suplementos', 'description' => null],
            ['name' => 'Material de Penso', 'description' => 'Curativos e materiais de penso'],
            ['name' => 'Dermatológicos', 'description' => 'Produtos para a pele'],
            ['name' => 'Pediátricos', 'description' => 'Medicamentos pediátricos'],
        ];

        foreach ($categories as $cat) {
            ProductCategory::firstOrCreate(['name' => $cat['name']], $cat);
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@mazefar.mz'],
            [
                'name'              => 'Administrador',
                'password'          => Hash::make('password'),
                'branch_id'         => $branch->id,
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('Administrador');

        $manager = User::firstOrCreate(
            ['email' => 'gerente@mazefar.mz'],
            [
                'name'              => 'Gerente Demo',
                'password'          => Hash::make('password'),
                'branch_id'         => $branch->id,
                'email_verified_at' => now(),
            ]
        );
        $manager->assignRole('Gerente');
    }
}
