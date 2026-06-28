<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'create:admin';
    protected $description = 'Create admin user';

    public function handle()
    {
        $this->info('Creating admin user...');

        try {
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

            $this->info('Admin user created successfully!');
            $this->info('Email: admin@admin.com');
            $this->info('Password: 123456');
        } catch (\Exception $e) {
            $this->error('Error creating admin user: ' . $e->getMessage());
        }
    }
} 