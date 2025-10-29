# POA Churrasco - Deploy no Coolify

## ✅ Status Atual
- **PostgreSQL**: ✅ Funcionando
- **Redis**: ✅ Funcionando  
- **PHP-FPM**: ✅ Funcionando na porta 9000
- **Aplicação**: ✅ Respondendo

## 🔧 Configuração no Coolify

### 1. Variáveis de Ambiente
Configure estas variáveis no Coolify:

```
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=poachurrasco
DB_USERNAME=postgres
DB_PASSWORD=sua_senha_aqui

REDIS_HOST=redis
REDIS_PORT=6379
REDIS_PASSWORD=null

APP_NAME="POA Churrasco"
APP_ENV=production
APP_KEY=base64:sua_chave_aqui
APP_DEBUG=false
APP_URL=https://poacapitalmundial.conext.click
```

### 2. Configuração do Proxy
O Coolify deve rotear requisições para o PHP-FPM na porta 9000.

### 3. Arquivos Necessários
- `index.php` - Aplicação PHP principal
- `coolify.conf` - Configuração do Nginx (se necessário)

## 🚀 Teste
Acesse: `https://poacapitalmundial.conext.click`

Deve mostrar:
- POA Churrasco - Sistema Funcionando!
- Status do PostgreSQL: OK
- Status do Redis: OK
- Versão do PHP
- Data/hora atual
