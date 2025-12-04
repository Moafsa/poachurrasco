@echo off
echo Limpando cache do Laravel...
echo.

REM Limpar cache de views
if exist storage\framework\views\*.php (
    del /Q storage\framework\views\*.php
    echo Cache de views limpo!
) else (
    echo Nenhum arquivo de cache de views encontrado.
)

REM Limpar cache de config
if exist bootstrap\cache\config.php (
    del /Q bootstrap\cache\config.php
    echo Cache de config limpo!
)

REM Limpar cache geral
if exist storage\framework\cache\data\* (
    rmdir /S /Q storage\framework\cache\data
    echo Cache de dados limpo!
)

echo.
echo Cache limpo! Agora:
echo 1. Recarregue a pagina no navegador (Ctrl+F5)
echo 2. Se ainda nao aparecer, feche e abra o navegador
pause






