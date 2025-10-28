# Resolvendo Erro "Missing required parameter: redirect_uri"

## ✅ Configuração Local Está Correta

O Laravel está configurado corretamente:
- **APP_URL**: `http://localhost:8000`
- **Google Redirect**: `http://localhost:8000/auth/google/callback`

## 🔧 Solução: Configurar Google Cloud Console

### 1. Acesse o Google Cloud Console
- Vá para: https://console.cloud.google.com/
- Selecione seu projeto

### 2. Configure OAuth 2.0 Client IDs
- Vá para "APIs & Services" > "Credentials"
- Clique no seu OAuth 2.0 Client ID existente

### 3. Adicione URIs de Redirecionamento Autorizados
Na seção "URIs de redirecionamento autorizados", adicione EXATAMENTE:
```
http://localhost:8000/auth/google/callback
```

### 4. Verifique as Configurações
- **Tipo de aplicativo**: Aplicativo da Web
- **Nome**: POA Capital do Churrasco
- **URIs de redirecionamento autorizados**: 
  - `http://localhost:8000/auth/google/callback`
- **Origens JavaScript autorizadas**:
  - `http://localhost:8000`

### 5. Salve as Alterações
- Clique em "Salvar"
- Aguarde alguns minutos para a propagação

## 🚨 Erros Comuns

### ❌ URIs Incorretas
- `http://localhost:8000/auth/google/callback/` (com barra no final)
- `https://localhost:8000/auth/google/callback` (HTTPS em desenvolvimento)
- `http://127.0.0.1:8000/auth/google/callback` (IP em vez de localhost)

### ✅ URI Correta
- `http://localhost:8000/auth/google/callback` (exatamente assim)

## 🔍 Verificação Adicional

### 1. Verifique se a API está Ativada
- Vá para "APIs & Services" > "Library"
- Procure por "Google+ API" ou "Google Identity"
- Certifique-se de que está ativada

### 2. Teste a Configuração
Após salvar no Google Console:
1. Aguarde 2-3 minutos
2. Teste o login novamente
3. Se ainda der erro, verifique se não há espaços extras nas URIs

## 📝 Para Produção

Quando for para produção, adicione também:
```
https://seudominio.com/auth/google/callback
```

## 🆘 Se Ainda Não Funcionar

1. **Verifique os logs do Laravel**:
   ```bash
   docker-compose exec app tail -f storage/logs/laravel.log
   ```

2. **Teste com uma nova chave**:
   - Crie um novo OAuth 2.0 Client ID
   - Use as mesmas configurações
   - Atualize o `.env` com as novas credenciais

3. **Verifique se não há cache**:
   ```bash
   docker-compose exec app php artisan config:clear
   docker-compose exec app php artisan cache:clear
   ```


