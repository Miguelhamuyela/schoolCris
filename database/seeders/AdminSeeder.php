<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Criar usuário admin
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456'),
            'email_verified_at' => now()
        ]);

        // Criar papel admin
        $adminRole = Role::create(['name' => 'admin']);

        // Associar papel ao usuário
        UserRole::create([
            'fk_users_id' => $user->id,
            'fk_roles_id' => $adminRole->id
        ]);
    }
} 