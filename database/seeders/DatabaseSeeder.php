<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Schoolyear;
use App\Models\Student;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);

        // Criar/atualizar um utilizador admin fixo
        $adminRole = Role::where('name', 'admin')->first();
        $user = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('12345678'),
            ]
        );

        UserRole::firstOrCreate([
            'fk_users_id' => $user->id,
            'fk_roles_id' => $adminRole->id,
        ]);

        // Criar utilizador secretaria
        $secretariaRole = Role::where('name', 'Secretaria')->first();
        $secretariaUser = User::updateOrCreate(
            ['email' => 'secretaria@admin.com'],
            [
                'name' => 'Secretaria',
                'password' => Hash::make('12345678'),
            ]
        );
        UserRole::firstOrCreate([
            'fk_users_id' => $secretariaUser->id,
            'fk_roles_id' => $secretariaRole->id,
        ]);
    }
}
