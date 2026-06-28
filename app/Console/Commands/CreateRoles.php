<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;

class CreateRoles extends Command
{
    protected $signature = 'create:roles';
    protected $description = 'Criar todos os papéis de usuário (admin, Secretaria)';

    public function handle()
    {
        $this->info('Criando papéis de usuário...');

        try {
            // Verificar se os roles já existem antes de criar
            if (!Role::where('name', 'admin')->exists()) {
                Role::create(['name' => 'admin']);
                $this->info('Papel "admin" criado com sucesso!');
            } else {
                $this->info('Papel "admin" já existe!');
            }
            
            if (!Role::where('name', 'Secretaria')->exists()) {
                Role::create(['name' => 'Secretaria']);
                $this->info('Papel "Secretaria" criado com sucesso!');
            } else {
                $this->info('Papel "Secretaria" já existe!');
            }

            $this->info('Todos os papéis foram verificados/criados com sucesso!');
            
        } catch (\Exception $e) {
            $this->error('Erro ao criar papéis: ' . $e->getMessage());
        }
    }
} 