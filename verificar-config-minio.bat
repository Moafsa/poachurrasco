@echo off
echo ========================================
echo   Verificando Configuracao MinIO
echo ========================================
echo.

echo Verificando se o container esta rodando...
docker-compose ps app

echo.
echo Testando conexao com MinIO...
docker-compose exec app php artisan tinker --execute="use Illuminate\Support\Facades\Storage; try { Storage::disk('minio')->put('test/connection-test.txt', 'Test connection'); echo 'SUCCESS: Arquivo criado no MinIO!'; Storage::disk('minio')->exists('test/connection-test.txt') ? print('SUCCESS: Arquivo existe!') : print('ERROR: Arquivo nao encontrado!'); echo 'URL: ' . Storage::disk('minio')->url('test/connection-test.txt'); } catch (Exception $e) { echo 'ERROR: ' . $e->getMessage(); }"

echo.
pause


















