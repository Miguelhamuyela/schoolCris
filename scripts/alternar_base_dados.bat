@echo off
title Alternar Base de Dados - School Management
echo =============================================
echo  ALTERNAR ENTRE BASE LOCAL E CLOUD
echo =============================================
echo.
echo 1. Local (XAMPP - MySQL)
echo 2. Cloud (TiDB - atual)
echo.
set /p opcao="Escolha (1 ou 2): "

if "%opcao%"=="1" (
    echo.
    echo A alternar para base de dados LOCAL...
    if exist .env.cloud (
        copy .env .env.cloud.tmp
        copy .env.cloud .env
        move .env.cloud.tmp .env.cloud
        echo Ficheiro .env atualizado para LOCAL.
    )
    echo.
    echo  Base de dados alterada para LOCAL (127.0.0.1:3306)
) else if "%opcao%"=="2" (
    echo.
    echo A alternar para base de dados CLOUD...
    if exist .env.local (
        copy .env .env.local.tmp
        copy .env.local .env
        move .env.local.tmp .env.local
        echo Ficheiro .env atualizado para CLOUD.
    )
    echo.
    echo  Base de dados alterada para CLOUD (TiDB)
) else (
    echo Opcao invalida!
    pause
    exit /b 1
)

echo.
php artisan optimize:clear
echo.
echo Concluido!
pause
