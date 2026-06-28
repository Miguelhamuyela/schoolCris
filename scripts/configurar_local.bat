@echo off
title Configurar Base de Dados Local - School Management
echo =============================================
echo  A CONFIGURAR BASE DE DADOS LOCAL (XAMPP)
echo =============================================
echo.

REM Verificar se o MySQL esta a correr
echo [1/5] A verificar servidor MySQL...
mysqladmin ping -h 127.0.0.1 -u root --silent
if %ERRORLEVEL% NEQ 0 (
    echo  ERRO: MySQL nao esta a correr!
    echo  Por favor, inicie o XAMPP e ligue o MySQL.
    pause
    exit /b 1
)
echo  MySQL esta ativo!
echo.

REM Criar base de dados
echo [2/5] A criar base de dados 'schoolmanagement'...
mysql -h 127.0.0.1 -u root -e "CREATE DATABASE IF NOT EXISTS schoolmanagement CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
if %ERRORLEVEL% NEQ 0 (
    echo  ERRO ao criar base de dados!
    pause
    exit /b 1
)
echo  Base de dados criada com sucesso!
echo.

REM Copiar .env local
echo [3/5] A configurar ficheiro .env...
if not exist .env.local (
    copy .env .env.cloud
    echo  Backup do .env original guardado como .env.cloud
)
echo  .env configurado para MySQL local!
echo.

REM Limpar cache do Laravel
echo [4/5] A limpar cache...
php artisan optimize:clear
echo.

REM Executar migrations
echo [5/5] A executar migrations, seeders e importar dados...
php artisan migrate:fresh

if %ERRORLEVEL% NEQ 0 (
    echo  ERRO ao executar migrations!
    pause
    exit /b 1
)
echo.

REM Executar seeders para popular roles e admin
echo  A popular dados iniciais (roles, admin)...
php artisan db:seed
if %ERRORLEVEL% NEQ 0 (
    echo  ERRO ao executar seeders!
    pause
    exit /b 1
)
echo.

REM Importar dados existentes do backup
echo  A importar dados do backup...
mysql -h 127.0.0.1 -u root schoolmanagement < "database\Todos os dados de schoolmanagement.sql"
if %ERRORLEVEL% NEQ 0 (
    echo  AVISO: Alguns dados podem nao ter sido importados.
    echo  Isto e normal se o backup for de uma versao anterior.
) else (
    echo  Dados importados com sucesso!
)
echo.

echo =============================================
echo  CONFIGURACAO CONCLUIDA COM SUCESSO!
echo =============================================
echo.
echo  Para iniciar o servidor:
echo    php artisan serve
echo.
echo  Ou use:  .\iniciar_servidor.bat
echo.
pause
