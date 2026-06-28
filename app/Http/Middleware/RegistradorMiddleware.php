<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegistradorMiddleware
{
    private $writeActions = ['.create', '.store', '.edit', '.update', '.destroy'];

    private $readOnlyResources = [
        'admin.schoolyear',
        'admin.course',
        'admin.classe',
        'admin.grade',
        'admin.transport',
        'admin.exam',
        'admin.rule',
        'admin.teacher',
        'admin.teacherSubjectClasseRuleSchoolyear',
        'admin.teacherSchoolyear',
        'admin.courseSubjectGrade',
    ];

    private $fullyBlockedResources = [
        'admin.user',
        'admin.teacher',
        'admin.exam',
        'admin.rule',
        'admin.teacherSubjectClasseRuleSchoolyear',
        'admin.teacherSchoolyear',
        'admin.courseSubjectGrade',
    ];

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user->roles->contains('name', 'admin') || 
            $user->roles->contains('name', 'Secretaria')) {

            if ($user->roles->contains('name', 'admin')) {
                return $next($request);
            }

            $routeName = $request->route() ? $request->route()->getName() : '';

            if ($user->roles->contains('name', 'Secretaria')) {
                $blocked = [
                    'admin.user.',
                    'admin.teacher.',
                    'admin.exam.',
                    'admin.rule.',
                    'admin.teacherSubjectClasseRuleSchoolyear.',
                    'admin.teacherSchoolyear.',
                    'admin.courseSubjectGrade.',
                ];

                foreach ($blocked as $prefix) {
                    if (Str::startsWith($routeName, $prefix)) {
                        abort(403, 'Acesso restrito. Esta área é reservada à Administração.');
                    }
                }

                // Read-only resources: allow list/show, block write actions
                $readOnlyResources = [
                    'admin.schoolyear.',
                    'admin.course.',
                    'admin.classe.',
                    'admin.grade.',
                    'admin.transport.',
                ];

                foreach ($readOnlyResources as $resource) {
                    if (Str::startsWith($routeName, $resource)) {
                        foreach ($this->writeActions as $action) {
                            if (Str::endsWith($routeName, $action)) {
                                abort(403, 'Acesso restrito. Apenas visualização permitida.');
                            }
                        }
                    }
                }
            }

            return $next($request);
        }

        abort(403, 'Acesso restrito. Área reservada a Administradores e Secretárias.');
    }
}
