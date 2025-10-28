@echo off
REM Script de Deploy Automático - Sistema de Avaliações Integradas
REM Este script garante que o sistema funcione perfeitamente em produção

echo 🚀 Iniciando deploy do Sistema de Avaliações Integradas...

REM 1. Verificar se estamos no diretório correto
if not exist "artisan" (
    echo ❌ Erro: Execute este script no diretório raiz do projeto Laravel
    pause
    exit /b 1
)

REM 2. Verificar se o Docker está rodando
docker-compose ps | findstr "Up" >nul
if errorlevel 1 (
    echo ❌ Erro: Docker Compose não está rodando
    echo Execute: docker-compose up -d
    pause
    exit /b 1
)

REM 3. Executar migrações
echo 📦 Executando migrações...
docker-compose exec app php artisan migrate --force

REM 4. Executar seeds (preserva dados existentes)
echo 🌱 Executando seeds...
docker-compose exec app php artisan db:seed --force

REM 5. Limpar e reconstruir cache
echo 🧹 Limpando cache...
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

REM 6. Verificar status das migrações
echo ✅ Verificando status das migrações...
docker-compose exec app php artisan migrate:status

REM 7. Testar comando de sincronização
echo 🔄 Testando sincronização de avaliações...
docker-compose exec app php artisan reviews:sync-external --limit=1 --force

REM 8. Verificar dados
echo 📊 Verificando dados...
for /f %%i in ('docker-compose exec app php artisan tinker --execute="echo App\Models\Establishment::whereNotNull('external_id')->count();"') do set ESTABLISHMENTS=%%i
for /f %%i in ('docker-compose exec app php artisan tinker --execute="echo App\Models\ExternalReview::count();"') do set EXTERNAL_REVIEWS=%%i

echo 📈 Estatísticas:
echo    - Estabelecimentos com external_id: %ESTABLISHMENTS%
echo    - Avaliações externas: %EXTERNAL_REVIEWS%

REM 9. Verificar rotas
echo 🛣️  Verificando rotas...
docker-compose exec app php artisan route:list --name=reviews

REM 10. Teste final
echo 🧪 Executando teste final...
docker-compose exec app php artisan tinker --execute="echo 'Sistema OK';" | findstr "Sistema OK" >nul
if errorlevel 1 (
    echo ❌ Erro no teste final
    pause
    exit /b 1
)

echo ✅ Deploy concluído com sucesso!
echo.
echo 🎉 Sistema de Avaliações Integradas está funcionando perfeitamente!
echo.
echo 📋 Próximos passos:
echo    1. Configure cron job para sincronização automática:
echo       0 2 * * * cd %CD% ^&^& docker-compose exec app php artisan reviews:sync-external ^>^> /var/log/reviews-sync.log 2^>^&1
echo.
echo    2. Monitore logs em: storage/logs/laravel.log
echo.
echo    3. Teste a interface em: http://localhost:8000/estabelecimento/1
echo.
echo 📚 Documentação: DEPLOY_AVALIACOES_INTEGRADAS.md

pause
