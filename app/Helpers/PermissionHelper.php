<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Verifica se o usuário atual pode realizar ações estruturais
     * (criar, editar, excluir cursos, turmas, disciplinas, etc.)
     *
     * @return bool
     */
    public static function canPerformStructuralActions()
    {
        // Apenas administradores podem realizar ações estruturais
        return Auth::check() && Auth::user()->roles->contains('name', 'admin');
    }
    
    /**
     * Verifica se o usuário é da Secretaria
     *
     * @return bool
     */
    public static function isSecretaria()
    {
        return Auth::check() && Auth::user()->roles->contains('name', 'Secretaria');
    }
    
    /**
     * Verifica se o usuário é admin
     *
     * @return bool
     */
    public static function isAdmin()
    {
        return Auth::check() && Auth::user()->roles->contains('name', 'admin');
    }
} 