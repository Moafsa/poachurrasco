# 🔧 Resolução do Erro "InvalidKeyMapError" - Google Maps

## ❌ Problema Identificado

O erro "InvalidKeyMapError" no console do navegador indica que a chave da API do Google Maps não está sendo aceita pelo Google. Isso geralmente acontece por problemas de configuração no Google Cloud Console.

## ✅ Soluções Implementadas

### 1. Configuração Corrigida
- ✅ Adicionada configuração `maps_api_key` no `config/services.php`
- ✅ Template atualizado para usar `config('services.google.maps_api_key')`
- ✅ Cache limpo (`config:clear` e `view:clear`)
- ✅ Chave da API testada e funcionando via Geocoding API

### 2. Verificações Realizadas
- ✅ Chave carregada corretamente
- ✅ Template renderizando corretamente
- ✅ API respondendo com status "OK"

## 🔧 Configuração Necessária no Google Cloud Console

### 1. Acesse o Google Cloud Console
- URL: https://console.cloud.google.com/
- Selecione o projeto correto

### 2. Configure as Restrições da API Key
1. Vá em **"APIs & Services"** > **"Credentials"**
2. Clique na sua API Key
3. Em **"Application restrictions"**, selecione **"HTTP referrers (web sites)"**
4. Adicione os seguintes domínios:
   ```
   localhost:8000/*
   localhost:8000/mapa
   127.0.0.1:8000/*
   *.localhost:8000/*
   ```

### 3. Configure as Restrições de API
1. Em **"API restrictions"**, selecione **"Restrict key"**
2. Adicione as seguintes APIs:
   - **Maps JavaScript API** (obrigatório)
   - **Places API** (para busca de estabelecimentos)
   - **Geocoding API** (para conversão de endereços)

### 4. Salve as Configurações
- Clique em **"Save"**
- Aguarde alguns minutos para a propagação

## 🧪 Teste de Validação

### 1. Teste da API Key
```bash
# Execute este comando para testar a chave
docker-compose exec app php test_maps_key.php
```

### 2. Teste no Navegador
1. Acesse `http://localhost:8000/mapa`
2. Abra o DevTools (F12)
3. Verifique o console para erros
4. A chave deve aparecer na URL do Google Maps API

## 🚨 Possíveis Causas do Erro

### 1. Restrições de Domínio
- **Problema**: A API key não está configurada para aceitar `localhost:8000`
- **Solução**: Adicionar `localhost:8000/*` nas restrições HTTP referrers

### 2. APIs Não Habilitadas
- **Problema**: Maps JavaScript API não está habilitada
- **Solução**: Habilitar "Maps JavaScript API" no Google Cloud Console

### 3. Quota Excedida
- **Problema**: Limite de requisições excedido
- **Solução**: Verificar quotas no Google Cloud Console

### 4. Chave Inválida
- **Problema**: Chave incorreta ou expirada
- **Solução**: Gerar nova chave de API

## 📋 Checklist de Verificação

- [ ] API Key configurada no `.env`
- [ ] Configuração adicionada no `config/services.php`
- [ ] Template atualizado para usar `config()`
- [ ] Cache limpo (`config:clear` e `view:clear`)
- [ ] Domínio `localhost:8000` adicionado nas restrições
- [ ] Maps JavaScript API habilitada
- [ ] Quotas não excedidas
- [ ] Aguardado propagação das configurações (5-10 minutos)

## 🔄 Próximos Passos

1. **Configure as restrições no Google Cloud Console**
2. **Aguarde 5-10 minutos para propagação**
3. **Teste novamente no navegador**
4. **Se persistir, verifique as quotas e logs no Google Cloud Console**

## 📞 Suporte Adicional

Se o problema persistir após seguir todos os passos:
1. Verifique os logs no Google Cloud Console
2. Teste com uma nova API key
3. Verifique se há cobranças pendentes na conta Google
4. Consulte a documentação oficial: https://developers.google.com/maps/documentation/javascript/error-messages
