# Configuração do Google Maps

Para integrar o Google Maps ao sistema, você precisa:

## 1. Criar um projeto no Google Cloud Console

1. Acesse: https://console.cloud.google.com/
2. Crie um novo projeto ou selecione um existente
3. Ative a API "Maps JavaScript API"

## 2. Configurar chave de API

1. Vá para "APIs & Services" > "Credentials"
2. Clique em "Create Credentials" > "API Key"
3. Configure as restrições de segurança:
   - Restrição de aplicativo: HTTP referrers
   - Adicione os domínios:
     - `localhost:8000/*` (desenvolvimento)
     - `seudominio.com/*` (produção)

## 3. Configurar variáveis de ambiente

Adicione ao seu arquivo .env:

```env
GOOGLE_MAPS_API_KEY=your-google-maps-api-key-here
```

## 4. Funcionalidades Implementadas

### Mapa Interativo
- Visualização de estabelecimentos em Porto Alegre
- Marcadores personalizados por categoria
- InfoWindow com detalhes do estabelecimento
- Controles de zoom e localização

### Sistema de Filtros
- Filtro por categoria (churrascaria, açougue, etc.)
- Filtro por faixa de preço ($, $$, $$$, $$$$)
- Filtro por avaliação mínima
- Filtro por comodidades (estacionamento, Wi-Fi, etc.)

### Busca e Navegação
- Busca por nome ou endereço
- Localização do usuário
- Lista lateral com estabelecimentos
- Integração com página de detalhes

### Sistema de Favoritos
- Adicionar/remover estabelecimentos dos favoritos
- Página dedicada para favoritos
- Sincronização com o mapa

## 5. Coordenadas de Porto Alegre

- **Centro da cidade**: -30.0346, -51.2177
- **Zoom recomendado**: 13-15
- **Área coberta**: Região metropolitana

## 6. Estabelecimentos de Exemplo

O sistema inclui estabelecimentos de exemplo em diferentes regiões:
- Centro Histórico
- Cidade Baixa
- Bom Fim
- Independência

## Notas Importantes

- Para produção, configure restrições de API adequadas
- Monitore o uso da API para evitar custos excessivos
- Considere implementar cache para melhorar performance
- Configure HTTPS em produção para geolocalização


