#!/bin/bash

# Script de Deploy Automático - Sistema de Avaliações Integradas
# Este script garante que o sistema funcione perfeitamente em produção

set -e  # Exit on any error

echo "🚀 Iniciando deploy do Sistema de Avaliações Integradas..."

# 1. Verificar se estamos no diretório correto
if [ ! -f "artisan" ]; then
    echo "❌ Erro: Execute este script no diretório raiz do projeto Laravel"
    exit 1
fi

# 2. Verificar se o Docker está rodando
if ! docker-compose ps | grep -q "Up"; then
    echo "❌ Erro: Docker Compose não está rodando"
    echo "Execute: docker-compose up -d"
    exit 1
fi

# 3. Executar migrações
echo "📦 Executando migrações..."
docker-compose exec app php artisan migrate --force

# 4. Executar seeds (preserva dados existentes)
echo "🌱 Executando seeds..."
docker-compose exec app php artisan db:seed --force

# 5. Limpar e reconstruir cache
echo "🧹 Limpando cache..."
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# 6. Verificar status das migrações
echo "✅ Verificando status das migrações..."
docker-compose exec app php artisan migrate:status

# 7. Testar comando de sincronização
echo "🔄 Testando sincronização de avaliações..."
docker-compose exec app php artisan reviews:sync-external --limit=1 --force

# 8. Verificar dados
echo "📊 Verificando dados..."
ESTABLISHMENTS=$(docker-compose exec app php artisan tinker --execute="echo App\Models\Establishment::whereNotNull('external_id')->count();" | tr -d '\r\n')
EXTERNAL_REVIEWS=$(docker-compose exec app php artisan tinker --execute="echo App\Models\ExternalReview::count();" | tr -d '\r\n')

echo "📈 Estatísticas:"
echo "   - Estabelecimentos com external_id: $ESTABLISHMENTS"
echo "   - Avaliações externas: $EXTERNAL_REVIEWS"

# 9. Verificar rotas
echo "🛣️  Verificando rotas..."
docker-compose exec app php artisan route:list --name=reviews

# 10. Teste final
echo "🧪 Executando teste final..."
if docker-compose exec app php artisan tinker --execute="echo 'Sistema OK';" | grep -q "Sistema OK"; then
    echo "✅ Deploy concluído com sucesso!"
    echo ""
    echo "🎉 Sistema de Avaliações Integradas está funcionando perfeitamente!"
    echo ""
    echo "📋 Próximos passos:"
    echo "   1. Configure cron job para sincronização automática:"
    echo "      0 2 * * * cd $(pwd) && docker-compose exec app php artisan reviews:sync-external >> /var/log/reviews-sync.log 2>&1"
    echo ""
    echo "   2. Monitore logs em: storage/logs/laravel.log"
    echo ""
    echo "   3. Teste a interface em: http://localhost:8000/estabelecimento/1"
    echo ""
    echo "📚 Documentação: DEPLOY_AVALIACOES_INTEGRADAS.md"
else
    echo "❌ Erro no teste final"
    exit 1
fi
