@echo off
echo ========================================
echo VERIFICANDO SE VITE ESTA RODANDO
echo ========================================
echo.

echo Testando conexao com Vite na porta 5173...
curl -s http://localhost:5173 >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo [OK] Vite esta rodando na porta 5173
) else (
    echo [ERRO] Vite NAO esta rodando!
    echo.
    echo Execute em outro terminal: npm run dev
    echo.
)

echo.
echo Verificando se o servidor Laravel esta rodando...
curl -s http://localhost:8000 >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo [OK] Laravel esta rodando na porta 8000
) else (
    echo [ERRO] Laravel NAO esta rodando!
    echo.
    echo Execute: php artisan serve
    echo.
)

echo.
echo ========================================
echo INSTRUCOES:
echo ========================================
echo.
echo 1. Abra DOIS terminais:
echo    - Terminal 1: npm run dev
echo    - Terminal 2: php artisan serve
echo.
echo 2. Deixe AMBOS rodando
echo.
echo 3. Acesse: http://localhost:8000
echo.
pause




















