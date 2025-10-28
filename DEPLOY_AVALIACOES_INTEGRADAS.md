# Deploy Configuration - Sistema de Avaliações Integradas

## Script de Deploy Automático

Este arquivo contém as instruções para garantir que o sistema de avaliações integradas funcione perfeitamente em produção.

### 1. Migrações Automáticas

As seguintes migrações serão executadas automaticamente durante o deploy:

```bash
# Executar migrações (já incluído no processo de deploy)
php artisan migrate --force

# Verificar status das migrações
php artisan migrate:status
```

**Migrações Criadas:**
- `2025_10_28_190638_create_external_reviews_table` - Tabela de avaliações externas
- `2025_10_28_191154_add_external_review_fields_to_establishments_table` - Campos de estatísticas externas

### 2. Seeds Automáticos

O sistema inclui seeds inteligentes que:
- ✅ **Preservam dados existentes** - Não sobrescrevem dados já presentes
- ✅ **Funcionam apenas quando necessário** - Só executam se houver estabelecimentos com external_id
- ✅ **São idempotentes** - Podem ser executados múltiplas vezes sem problemas

```bash
# Executar seeds (já incluído no processo de deploy)
php artisan db:seed --force

# Ou executar seeders específicos
php artisan db:seed --class=ExternalReviewSeeder --force
```

### 3. Configuração de Ambiente

**Variáveis Obrigatórias (.env):**
```env
# Google Places API (já configurado)
GOOGLE_PLACES_API_KEY=your-google-places-api-key-here

# Cache (recomendado para produção)
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### 4. Comandos de Manutenção

**Sincronização Automática (Cron Job):**
```bash
# Adicionar ao crontab do servidor
0 2 * * * cd /path/to/project && php artisan reviews:sync-external >> /var/log/reviews-sync.log 2>&1
```

**Comandos de Verificação:**
```bash
# Verificar estabelecimentos com avaliações externas
php artisan tinker
>>> App\Models\Establishment::whereNotNull('external_id')->count()

# Verificar avaliações externas sincronizadas
>>> App\Models\ExternalReview::count()

# Testar sincronização
php artisan reviews:sync-external --limit=5
```

### 5. Monitoramento

**Logs Importantes:**
- `storage/logs/laravel.log` - Logs gerais do sistema
- `/var/log/reviews-sync.log` - Logs específicos da sincronização (se configurado)

**Métricas a Monitorar:**
- Número de estabelecimentos com external_id
- Número de avaliações externas sincronizadas
- Taxa de sucesso da sincronização
- Tempo de resposta das APIs

### 6. Troubleshooting

**Problemas Comuns e Soluções:**

1. **Erro de API Key:**
   ```bash
   # Verificar configuração
   php artisan tinker
   >>> config('services.google.places_api_key')
   ```

2. **Estabelecimentos sem external_id:**
   ```bash
   # Verificar estabelecimentos
   php artisan tinker
   >>> App\Models\Establishment::whereNull('external_id')->count()
   ```

3. **Falha na sincronização:**
   ```bash
   # Testar sincronização manual
   php artisan reviews:sync-external --establishment=1 --force
   ```

### 7. Backup e Restore

**Backup das Tabelas Importantes:**
```bash
# Backup das avaliações externas
mysqldump -u username -p database_name external_reviews > external_reviews_backup.sql

# Backup dos estabelecimentos (com novos campos)
mysqldump -u username -p database_name establishments > establishments_backup.sql
```

**Restore:**
```bash
# Restaurar avaliações externas
mysql -u username -p database_name < external_reviews_backup.sql

# Restaurar estabelecimentos
mysql -u username -p database_name < establishments_backup.sql
```

### 8. Performance

**Otimizações Implementadas:**
- ✅ Cache de 1 hora para avaliações externas
- ✅ Cache de 24 horas para detalhes de lugares
- ✅ Índices otimizados no banco de dados
- ✅ Sincronização em lotes
- ✅ Paginação nas APIs

**Monitoramento de Performance:**
```bash
# Verificar cache
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 9. Segurança

**Validações Implementadas:**
- ✅ Validação de entrada em todas as APIs
- ✅ Sanitização de dados externos
- ✅ Rate limiting nas APIs externas
- ✅ Logs de auditoria para sincronizações

### 10. Rollback (se necessário)

**Reverter Migrações:**
```bash
# Reverter última migração
php artisan migrate:rollback --step=1

# Reverter múltiplas migrações
php artisan migrate:rollback --step=2
```

**Limpar Dados de Teste:**
```bash
# Limpar avaliações externas de teste
php artisan tinker
>>> App\Models\ExternalReview::where('external_source', 'google_places')->delete()
```

## Checklist de Deploy

- [ ] Migrações executadas com sucesso
- [ ] Seeds executados sem erros
- [ ] Variáveis de ambiente configuradas
- [ ] Cache limpo e reconstruído
- [ ] Rotas registradas corretamente
- [ ] Comando de sincronização testado
- [ ] Logs funcionando
- [ ] Monitoramento configurado

## Suporte

Em caso de problemas durante o deploy:
1. Verificar logs em `storage/logs/laravel.log`
2. Executar `php artisan migrate:status` para verificar migrações
3. Testar comandos manualmente
4. Verificar configuração do ambiente
5. Consultar documentação em `SISTEMA_AVALIACOES_INTEGRADAS.md`
