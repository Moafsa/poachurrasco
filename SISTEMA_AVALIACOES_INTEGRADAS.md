# Sistema de Avaliações Integradas - POA Churrasco

## Visão Geral

O sistema de avaliações integradas combina avaliações internas (feitas pelos usuários do site) com avaliações externas (do Google Places) para fornecer uma visão completa e confiável sobre cada estabelecimento.

## Funcionalidades Implementadas

### 1. Modelos de Dados

#### ExternalReview Model
- Armazena avaliações vindas do Google Places API
- Campos: author_name, rating, text, time, profile_photo_url, etc.
- Relacionamento com Establishment

#### Establishment Model (Atualizado)
- Novos campos: external_rating, external_review_count
- Relacionamentos com reviews internas e externas
- Método allReviews() para combinar ambos os tipos

### 2. Serviços

#### ReviewService
- `syncExternalReviews()`: Sincroniza avaliações do Google Places
- `getCombinedReviews()`: Retorna avaliações internas + externas
- `getOverallRating()`: Calcula rating geral combinado
- `bulkSyncExternalReviews()`: Sincronização em lote

#### EstablishmentApiService (Atualizado)
- Método `syncExternalReviews()` para integração com ReviewService

### 3. Controladores

#### ReviewController (Atualizado)
- `getCombinedReviews()`: API endpoint para buscar avaliações combinadas
- `syncExternalReviews()`: API endpoint para sincronizar avaliações externas
- `updateEstablishmentRating()`: Atualiza rating considerando ambos os tipos

### 4. Interface do Usuário

#### Página de Detalhes do Estabelecimento
- Exibe avaliações internas e externas de forma integrada
- Botão "Atualizar Avaliações" para sincronizar com Google Places
- Carregamento dinâmico via JavaScript
- Diferenciação visual entre tipos de avaliação (badge "Google")

### 5. Comandos Artisan

#### reviews:sync-external
```bash
# Sincronizar todas as avaliações externas
php artisan reviews:sync-external

# Sincronizar estabelecimento específico
php artisan reviews:sync-external --establishment=123

# Forçar sincronização (ignorar cache de 24h)
php artisan reviews:sync-external --force

# Limitar número de estabelecimentos
php artisan reviews:sync-external --limit=10
```

## Como Usar

### 1. Sincronização Manual
```bash
# Via comando Artisan
php artisan reviews:sync-external

# Via interface web (botão "Atualizar Avaliações")
# Disponível na página de detalhes do estabelecimento
```

### 2. Sincronização Automática
```bash
# Adicionar ao cron para sincronização diária
0 2 * * * cd /path/to/project && php artisan reviews:sync-external
```

### 3. API Endpoints

#### Buscar Avaliações Combinadas
```http
GET /api/establishments/{id}/reviews
```

Resposta:
```json
{
  "reviews": [
    {
      "id": 1,
      "type": "internal",
      "author_name": "João Silva",
      "rating": 5,
      "text": "Excelente churrascaria!",
      "time_ago": "2 dias atrás",
      "is_verified": true
    },
    {
      "id": 2,
      "type": "external",
      "author_name": "Maria Santos",
      "rating": 4,
      "text": "Muito bom!",
      "time_ago": "1 semana atrás",
      "is_verified": true
    }
  ],
  "overall_rating": {
    "rating": 4.5,
    "total_reviews": 2,
    "internal_reviews": 1,
    "external_reviews": 1
  }
}
```

#### Sincronizar Avaliações Externas
```http
POST /api/establishments/{id}/sync-reviews
```

## Estrutura do Banco de Dados

### Tabela: external_reviews
```sql
CREATE TABLE external_reviews (
    id BIGINT PRIMARY KEY,
    establishment_id BIGINT,
    external_id VARCHAR(255) UNIQUE,
    external_source VARCHAR(50) DEFAULT 'google_places',
    author_name VARCHAR(255),
    author_url VARCHAR(500),
    profile_photo_url VARCHAR(500),
    rating INTEGER,
    text TEXT,
    time TIMESTAMP,
    language VARCHAR(10),
    original_data JSON,
    is_verified BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Tabela: establishments (campos adicionados)
```sql
ALTER TABLE establishments ADD COLUMN external_rating DECIMAL(3,2);
ALTER TABLE establishments ADD COLUMN external_review_count INTEGER DEFAULT 0;
```

## Configuração

### Variáveis de Ambiente
```env
GOOGLE_PLACES_API_KEY=your-google-places-api-key-here
```

### Cache
- Avaliações externas são cacheadas por 1 hora
- Detalhes de lugares são cacheados por 24 horas
- Use `--force` para ignorar cache

## Monitoramento

### Logs
- Sincronizações são logadas em `storage/logs/laravel.log`
- Erros de API são registrados com detalhes

### Métricas
- Total de avaliações sincronizadas
- Taxa de sucesso/falha
- Tempo de sincronização

## Troubleshooting

### Problemas Comuns

1. **Erro de API Key**
   - Verificar se `GOOGLE_PLACES_API_KEY` está configurada
   - Verificar se a chave tem permissões para Places API

2. **Rate Limiting**
   - Google Places API tem limites de requisições
   - Implementar delays entre requisições se necessário

3. **Estabelecimentos sem external_id**
   - Apenas estabelecimentos com `external_id` podem sincronizar avaliações
   - Verificar se o estabelecimento foi importado do Google Places

### Comandos de Diagnóstico
```bash
# Verificar estabelecimentos com external_id
php artisan tinker
>>> App\Models\Establishment::whereNotNull('external_id')->count()

# Verificar avaliações externas sincronizadas
>>> App\Models\ExternalReview::count()

# Testar sincronização de um estabelecimento específico
php artisan reviews:sync-external --establishment=1
```

## Próximos Passos

1. **Automação**: Configurar cron job para sincronização diária
2. **Monitoramento**: Implementar alertas para falhas de sincronização
3. **Analytics**: Dashboard com estatísticas de avaliações
4. **Integração**: Adicionar outras fontes (TripAdvisor, Yelp, etc.)
5. **Moderação**: Sistema para moderar avaliações externas
6. **Notificações**: Alertar estabelecimentos sobre novas avaliações

## Considerações de Performance

- Cache agressivo para reduzir chamadas à API
- Sincronização em lotes para grandes volumes
- Índices otimizados para consultas frequentes
- Paginação nas APIs para grandes conjuntos de dados
