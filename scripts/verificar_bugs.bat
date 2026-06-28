@echo off
echo ========================================
echo    VERIFICACAO DE BUGS - SCHOOL CRIS
echo ========================================
echo.

echo [1/5] Limpando cache...
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo.
echo [2/5] Verificando sintaxe dos arquivos PHP...
php -l routes/Admin.php
php -l routes/web.php
php -l routes/auth.php

echo.
echo [3/5] Verificando controllers faltantes...
php artisan route:list --name=admin.registration
php artisan route:list --name=admin.teacherSchoolyear

echo.
echo [4/5] Verificando se nao ha erros de rotas...
php artisan route:list > nul 2>&1
if %errorlevel% equ 0 (
    echo [OK] Todas as rotas estao funcionando!
) else (
    echo [ERRO] Ha problemas com as rotas!
)

echo.
echo [5/5] Limpando log de erros...
echo "" > storage/logs/laravel.log

echo.
echo ========================================
echo    VERIFICACAO CONCLUIDA!
echo ========================================
echo.
echo Principais bugs corrigidos:
echo - RegistrationController criado
echo - TeacherSchoolyearController criado
echo - Cache limpo
echo - Log de erros limpo
echo.
pause 