# Sistema de Integração com APIs Externas - PoaChurras

## 🎯 Objetivo

Este sistema integra com APIs externas (Google Places) para buscar estabelecimentos reais de Porto Alegre, evitando buscas recorrentes e mantendo os dados atualizados automaticamente.

## 🚀 Funcionalidades Implementadas

### ✅ Sincronização Automática
- Busca estabelecimentos reais de Porto Alegre
- Atualiza dados existentes periodicamente
- Remove estabelecimentos fechados
- Executa a cada hora automaticamente

### ✅ Integração Inteligente
- Quando usuário cadastra estabelecimento, sistema busca dados externos automaticamente
- Mescla dados do usuário com dados da API
- Evita duplicação de estabelecimentos

### ✅ Processamento em Background
- Jobs para processamento assíncrono
- Retry automático em caso de falha
- Logs detalhados para monitoramento

### ✅ APIs Disponíveis
- Busca de estabelecimentos externos
- Importação de estabelecimentos específicos
- Sincronização manual
- Dados para exibição no mapa

## 📋 Pré-requisitos

### 1. Chaves de API
Você precisa configurar as seguintes variáveis no arquivo `.env`:

```env
# Google Places API (Obrigatório)
GOOGLE_PLACES_API_KEY=sua-chave-aqui

# Google OAuth (Opcional)
GOOGLE_CLIENT_ID=seu-client-id
GOOGLE_CLIENT_SECRET=seu-client-secret

# Foursquare API (Opcional)
FOURSQUARE_API_KEY=sua-chave-aqui
```

### 2. Como Obter a Chave do Google Places API

1. Acesse [Google Cloud Console](https://console.cloud.google.com/)
2. Crie um projeto ou selecione um existente
3. Ative a **Places API**
4. Vá em **Credenciais** → **Criar Credenciais** → **Chave de API**
5. Copie a chave e adicione ao arquivo `.env`

## 🛠️ Como Usar

### 1. Sincronização Automática

O sistema já está configurado para sincronizar automaticamente:

```bash
# Executa a cada hora
php artisan schedule:run

# Para testar manualmente
php artisan establishments:sync
```

### 2. Sincronização Manual

```bash
# Sincronização básica
php artisan establishments:sync

# Sincronização com refresh de dados existentes
php artisan establishments:sync --refresh

# Sincronização com parâmetros customizados
php artisan establishments:sync --search-terms="churrascaria,açougue" --radius=30000 --limit=50
```

### 3. Teste da Integração

```bash
# Testar a API
php artisan establishments:test-api

# Testar com parâmetros específicos
php artisan establishments:test-api --query="restaurante" --limit=3
```

### 4. APIs REST

#### Buscar Estabelecimentos Externos
```http
GET /dashboard/establishments/search/external?query=churrascaria&radius=50000
```

#### Importar Estabelecimento Específico
```http
POST /dashboard/establishments/import/external
Content-Type: application/json

{
    "external_id": "ChIJ...",
    "external_source": "google_places"
}
```

#### Sincronizar Estabelecimento
```http
POST /dashboard/establishments/{id}/sync
```

#### Dados para Mapa
```http
GET /dashboard/establishments/map/data?include_external=true&category=churrascaria
```

## 📊 Monitoramento

### 1. Logs
Os logs são salvos em `storage/logs/laravel.log`:

```bash
# Ver logs em tempo real
tail -f storage/logs/laravel.log

# Filtrar logs de sincronização
grep "establishment" storage/logs/laravel.log
```

### 2. Estatísticas do Banco
```bash
# Ver estatísticas
php artisan establishments:test-api
```

### 3. Jobs na Fila
```bash
# Processar jobs
php artisan queue:work

# Ver jobs falhados
php artisan queue:failed
```

## 🔧 Configuração Avançada

### 1. Frequência de Sincronização

Edite `routes/console.php` para alterar a frequência:

```php
// Sincronização a cada 30 minutos
Schedule::command('establishments:sync')
    ->everyThirtyMinutes()
    ->withoutOverlapping()
    ->runInBackground();
```

### 2. Cache

O sistema usa cache para evitar chamadas desnecessárias à API:

```php
// Cache por 1 hora (3600 segundos)
Cache::remember($cacheKey, 3600, function() {
    // Busca na API
});
```

### 3. Rate Limiting

Para evitar exceder limites da API:

```php
// Adicionar delay entre requisições
sleep(1); // 1 segundo entre requisições
```

## 🐛 Troubleshooting

### Problema: "API Key Inválida"
**Solução:**
1. Verifique se a chave está correta no `.env`
2. Verifique se a Places API está ativada
3. Verifique as restrições de uso da chave

### Problema: "Rate Limit Excedido"
**Solução:**
1. Aumente o cache time
2. Reduza a frequência de sincronização
3. Use jobs em background

### Problema: "Estabelecimentos Não Aparecem"
**Solução:**
1. Execute sincronização manual: `php artisan establishments:sync`
2. Verifique logs: `tail -f storage/logs/laravel.log`
3. Teste a API: `php artisan establishments:test-api`

## 📈 Próximos Passos

### Funcionalidades Planejadas
- [ ] Integração com Foursquare API
- [ ] Notificações de sincronização
- [ ] Métricas de performance
- [ ] Cache inteligente
- [ ] Mais fontes de dados

### Melhorias Técnicas
- [ ] Rate limiting inteligente
- [ ] Retry com backoff exponencial
- [ ] Monitoramento de saúde da API
- [ ] Dashboard de métricas

## 📞 Suporte

Para dúvidas ou problemas:

1. Verifique os logs em `storage/logs/laravel.log`
2. Execute o comando de teste: `php artisan establishments:test-api`
3. Verifique a documentação em `CONFIGURACAO_APIS_EXTERNAS.md`

## 🎉 Exemplo de Uso Completo

```bash
# 1. Configurar API key no .env
echo "GOOGLE_PLACES_API_KEY=sua-chave-aqui" >> .env

# 2. Executar migration
php artisan migrate

# 3. Testar integração
php artisan establishments:test-api

# 4. Executar sincronização inicial
php artisan establishments:sync --refresh

# 5. Verificar resultados
php artisan establishments:test-api --query="churrascaria" --limit=10
```

O sistema agora está pronto para buscar e manter estabelecimentos reais de Porto Alegre automaticamente! 🚀
