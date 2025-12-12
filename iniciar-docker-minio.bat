@echo off
echo ========================================
echo   Iniciando Docker com MinIO e Notificacoes
echo ========================================
echo.

echo [1/4] Parando containers existentes...
docker-compose down

echo.
echo [2/4] Reconstruindo imagens Docker...
docker-compose build

echo.
echo [3/4] Iniciando todos os servicos...
docker-compose up -d

echo.
echo [4/4] Verificando status dos containers...
docker-compose ps

echo.
echo ========================================
echo   Containers iniciados!
echo ========================================
echo.
echo Para ver os logs:
echo   docker-compose logs -f app
echo   docker-compose logs -f queue-worker
echo.
echo Para parar os containers:
echo   docker-compose down
echo.
pause

















