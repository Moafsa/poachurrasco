@echo off
echo ========================================
echo FORCANDO ATUALIZACAO COMPLETA
echo ========================================
echo.

echo [1/4] Limpando cache de views...
if exist storage\framework\views\*.php (
    del /F /Q storage\framework\views\*.php 2>nul
    echo OK - Views limpas
) else (
    echo OK - Nenhuma view em cache
)

echo.
echo [2/4] Limpando cache de config...
if exist bootstrap\cache\config.php (
    del /F /Q bootstrap\cache\config.php 2>nul
    echo OK - Config limpo
)

echo.
echo [3/4] Limpando cache de dados...
if exist storage\framework\cache\data (
    rmdir /S /Q storage\framework\cache\data 2>nul
    echo OK - Cache de dados limpo
)

echo.
echo [4/4] Recompilando assets...
call .\node_modules\.bin\vite build
if %ERRORLEVEL% EQU 0 (
    echo OK - Assets recompilados
) else (
    echo ERRO - Falha ao compilar assets
)

echo.
echo ========================================
echo CONCLUIDO!
echo ========================================
echo.
echo AGORA:
echo 1. Reinicie o servidor Laravel (Ctrl+C e depois php artisan serve)
echo 2. No navegador: Ctrl+Shift+Delete e limpe TUDO
echo 3. Feche e abra o navegador
echo 4. Acesse localhost:8000 novamente
echo.
pause






