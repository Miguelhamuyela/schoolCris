@echo off
echo ===============================================================
echo MIGRACAO DE TABELAS DO SISTEMA SCHOOLCRIS
echo ===============================================================
echo.
echo Executando migrações das novas tabelas...
echo - Teachers Courses
echo - Teachers Subjects
echo - Teachers Classes
echo - Teachers Grades (NOVA)
echo.
php artisan migrate
echo.
echo Migrando professores para tabela teachers...
php artisan professors:migrate
echo.
echo ===============================================================
echo Processo concluído!
echo Agora você pode cadastrar professores com associação a cursos, 
echo disciplinas, classes e turmas!
echo ===============================================================
echo.
echo Pressione qualquer tecla para sair...
pause > nul 