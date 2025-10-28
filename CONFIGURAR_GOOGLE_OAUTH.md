# Configuração do Google OAuth

Para configurar a autenticação Google, você precisa:

## 1. Criar um projeto no Google Cloud Console

1. Acesse: https://console.cloud.google.com/
2. Crie um novo projeto ou selecione um existente
3. Ative a API "Google+ API" ou "Google Identity"

## 2. Configurar OAuth 2.0

1. Vá para "APIs & Services" > "Credentials"
2. Clique em "Create Credentials" > "OAuth 2.0 Client IDs"
3. Configure:
   - Application type: Web application
   - Name: POA Capital do Churrasco
   - Authorized redirect URIs: 
     - http://localhost:8000/auth/google/callback (desenvolvimento)
     - https://seudominio.com/auth/google/callback (produção)

## 3. Configurar variáveis de ambiente

Adicione ao seu arquivo .env:

```env
GOOGLE_CLIENT_ID=your-google-client-id-here
GOOGLE_CLIENT_SECRET=your-google-client-secret-here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

## 4. Testar a autenticação

1. Acesse http://localhost:8000/login
2. Clique em "Continuar com Google"
3. Faça login com sua conta Google
4. Você será redirecionado para o dashboard

## Notas importantes

- Para produção, certifique-se de usar HTTPS
- Configure os domínios autorizados no Google Console
- Mantenha as credenciais seguras e nunca as commite no Git