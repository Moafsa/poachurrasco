# Configuração de APIs Externas - PoaChurras

## Visão Geral

O sistema PoaChurras agora integra com APIs externas para buscar estabelecimentos reais de Porto Alegre, evitando buscas recorrentes e mantendo os dados atualizados automaticamente.

## APIs Integradas

### 1. Google Places API
- **Propósito**: Buscar estabelecimentos reais de Porto Alegre
- **Funcionalidades**: 
  - Busca por texto (churrascarias, açougues, etc.)
  - Busca por proximidade
  - Detalhes completos dos estabelecimentos
  - Fotos, avaliações, horários de funcionamento

### 2. Foursquare API (Opcional)
- **Propósito**: Dados complementares de estabelecimentos
- **Status**: Configurado mas não implementado ainda

## Configuração das Variáveis de Ambiente

Adicione as seguintes variáveis ao seu arquivo `.env`:

```env
# Google Services Configuration
GOOGLE_CLIENT_ID=your-google-client-id-here
GOOGLE_CLIENT_SECRET=your-google-client-secret-here
GOOGLE_PLACES_API_KEY=your-google-places-api-key-here

# Foursquare API Configuration (optional)
FOURSQUARE_API_KEY=your-foursquare-api-key-here
```

## Como Obter as Chaves de API

### Google Places API Key
1. Acesse o [Google Cloud Console](https://console.cloud.google.com/)
2. Crie um novo projeto ou selecione um existente
3. Ative a API "Places API"
4. Vá em "Credenciais" e crie uma nova chave de API
5. Configure as restrições de uso (recomendado)

### Foursquare API Key (Opcional)
1. Acesse o [Foursquare Developer Portal](https://developer.foursquare.com/)
2. Crie uma conta de desenvolvedor
3. Crie um novo projeto
4. Obtenha sua chave de API

## Funcionalidades Implementadas

### 1. Sincronização Automática
- **Comando**: `php artisan establishments:sync`
- **Frequência**: A cada hora (configurável)
- **Funcionalidade**: Busca novos estabelecimentos e atualiza existentes

### 2. Sincronização Manual
- **Endpoint**: `POST /dashboard/establishments/{id}/sync`
- **Funcionalidade**: Sincroniza um estabelecimento específico

### 3. Busca Externa
- **Endpoint**: `GET /dashboard/establishments/search/external`
- **Parâmetros**: `query`, `radius`
- **Funcionalidade**: Busca estabelecimentos na API externa

### 4. Importação de Estabelecimentos
- **Endpoint**: `POST /dashboard/establishments/import/external`
- **Parâmetros**: `external_id`, `external_source`
- **Funcionalidade**: Importa um estabelecimento específico da API

### 5. Dados para Mapa
- **Endpoint**: `GET /dashboard/establishments/map/data`
- **Parâmetros**: `include_external`, `category`, `latitude`, `longitude`
- **Funcionalidade**: Retorna dados para exibição no mapa

## Processamento em Background

### Jobs Implementados
- **ProcessEstablishmentJob**: Processa estabelecimentos individualmente
- **Ações**: `sync`, `refresh`, `merge`

### Configuração do Queue
- **Driver**: Redis (recomendado)
- **Configuração**: `QUEUE_CONNECTION=redis`

## Estrutura do Banco de Dados

### Novos Campos Adicionados
- `external_id`: ID único da API externa
- `external_source`: Fonte da API (google_places, foursquare)
- `external_data`: Dados brutos da API
- `last_synced_at`: Última sincronização
- `is_external`: Se é estabelecimento externo
- `is_merged`: Se foi mesclado com dados do usuário
- `place_id`: Google Places place_id
- `business_status`: Status do negócio
- `types`: Tipos de negócio
- `price_level`: Nível de preço (0-4)
- `photos`: Fotos da API
- `reviews_external`: Avaliações externas
- `vicinity`: Vizinhança
- `formatted_address`: Endereço formatado
- `formatted_phone_number`: Telefone formatado
- `international_phone_number`: Telefone internacional
- `opening_hours_external`: Horários externos
- `permanently_closed`: Se está permanentemente fechado
- `user_ratings_total`: Total de avaliações

## Fluxo de Funcionamento

### 1. Cadastro de Estabelecimento pelo Usuário
1. Usuário cadastra estabelecimento
2. Sistema cria registro no banco
3. Job é disparado para buscar dados externos
4. Dados externos são mesclados automaticamente

### 2. Sincronização Periódica
1. Comando executa a cada hora
2. Busca novos estabelecimentos na API
3. Atualiza dados existentes
4. Remove estabelecimentos fechados

### 3. Atualização Manual
1. Usuário solicita sincronização
2. Job é disparado imediatamente
3. Dados são atualizados em background

## Comandos Disponíveis

### Sincronização Básica
```bash
php artisan establishments:sync
```

### Sincronização com Refresh
```bash
php artisan establishments:sync --refresh
```

### Sincronização com Parâmetros Customizados
```bash
php artisan establishments:sync --search-terms="churrascaria,açougue" --radius=30000 --limit=50
```

## Monitoramento e Logs

### Logs Importantes
- Sincronização de estabelecimentos
- Erros de API
- Jobs processados
- Estabelecimentos importados

### Métricas Disponíveis
- Total de estabelecimentos sincronizados
- Total de atualizações
- Total de erros
- Estabelecimentos fechados

## Troubleshooting

### Problemas Comuns

1. **API Key Inválida**
   - Verifique se a chave está correta
   - Verifique se a API está ativada
   - Verifique as restrições de uso

2. **Rate Limit Excedido**
   - Implemente cache adequado
   - Reduza frequência de sincronização
   - Use jobs em background

3. **Estabelecimentos Não Aparecem**
   - Verifique se a sincronização está funcionando
   - Verifique logs de erro
   - Execute sincronização manual

### Comandos de Debug
```bash
# Verificar logs
tail -f storage/logs/laravel.log

# Executar sincronização manual
php artisan establishments:sync --refresh

# Verificar jobs na fila
php artisan queue:work
```

## Próximos Passos

1. **Implementar Foursquare API**
2. **Adicionar mais fontes de dados**
3. **Implementar cache inteligente**
4. **Adicionar métricas de performance**
5. **Implementar notificações de sincronização**
