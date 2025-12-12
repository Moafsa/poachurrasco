# 🚀 Instruções de Execução - Docker MinIO e Notificações

## ✅ Consolidação Completa - Pronto para Executar!

Todas as configurações foram consolidadas no Docker. Segue o passo a passo:

---

## 📋 Passo 1: Configurar Variáveis de Ambiente

Edite ou crie o arquivo `.env` na raiz do projeto e adicione:

```env
# Storage Configuration
STORAGE_DISK=minio
FILESYSTEM_DISK=minio

# MinIO Configuration
MINIO_ACCESS_KEY=EwsP5sPulj1RNxy76tJA
MINIO_SECRET_KEY=w5RtVLMjx3DwP18L0BJhe5weU8ykL1EXCroXtanT
MINIO_BUCKET=poachurras
MINIO_ENDPOINT=https://winio.conext.click
MINIO_URL=https://winio.conext.click/poachurras
MINIO_REGION=us-east-1
MINIO_USE_PATH_STYLE=true

# Mail Configuration (ajustar conforme necessário)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=seu_usuario
MAIL_PASSWORD=sua_senha
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@poachurras.com
MAIL_FROM_NAME="Porto Alegre Capital Mundial do Churrasco"
```

---

## 🐳 Passo 2: Executar Docker

### Opção A: Usar Script Batch (Windows)

Execute o arquivo:
```
iniciar-docker-minio.bat
```

Este script irá:
1. Parar containers existentes
2. Reconstruir imagens
3. Iniciar todos os serviços
4. Mostrar status dos containers

### Opção B: Executar Manualmente

```bash
# 1. Parar containers existentes
docker-compose down

# 2. Reconstruir imagens
docker-compose build

# 3. Iniciar todos os serviços
docker-compose up -d

# 4. Verificar status
docker-compose ps
```

---

## 🔍 Passo 3: Verificar Logs

```bash
# Logs da aplicação
docker-compose logs -f app

# Logs do queue worker (notificações)
docker-compose logs -f queue-worker

# Logs de todos os serviços
docker-compose logs -f
```

Você deve ver:
- ✅ "Autoload dumped!" - Helper carregado
- ✅ "Cache optimized!" - Cache configurado
- ✅ Queue worker processando jobs

---

## ✅ Passo 4: Verificar Funcionamento

### Testar MinIO

Execute o script:
```
verificar-config-minio.bat
```

Ou manualmente:
```bash
docker-compose exec app php artisan tinker
```

Dentro do tinker:
```php
>>> Storage::disk('minio')->put('test/hello.txt', 'Hello MinIO');
>>> Storage::disk('minio')->exists('test/hello.txt');
>>> Storage::disk('minio')->url('test/hello.txt');
```

### Verificar Queue Worker

```bash
# Ver logs do queue worker
docker-compose logs queue-worker

# Deve mostrar algo como:
# Processing: App\Jobs\SendOrderConfirmationJob
# Processed:  App\Jobs\SendOrderConfirmationJob
```

---

## 📊 Estrutura de Serviços

Agora você tem 5 serviços rodando:

1. **app** - Aplicação Laravel (Nginx + PHP-FPM)
2. **db** - PostgreSQL
3. **redis** - Redis Cache
4. **vite** - Vite Dev Server
5. **queue-worker** - ✨ NOVO - Processa filas de notificações

---

## 🔧 Comandos Úteis

### Recarregar Autoload
```bash
docker-compose exec app composer dump-autoload --optimize
```

### Limpar Cache
```bash
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
```

### Reiniciar Queue Worker
```bash
docker-compose restart queue-worker
```

### Parar Tudo
```bash
docker-compose down
```

---

## 📝 O que foi Consolidado

### ✅ docker-compose.yml
- Variáveis de ambiente MinIO
- Variáveis de ambiente Mail configuráveis
- Novo serviço `queue-worker` para notificações
- Storage disk configurável via `.env`

### ✅ Dockerfile
- Script de inicialização atualizado
- `composer dump-autoload` executado automaticamente
- Helper `storage_helper.php` carregado automaticamente

### ✅ Arquivos Criados
- `iniciar-docker-minio.bat` - Script para iniciar tudo
- `verificar-config-minio.bat` - Script para testar MinIO
- Documentação completa

---

## ⚠️ Importante

1. **Primeira Execução**: Pode levar alguns minutos para construir as imagens
2. **Autoload**: Será recarregado automaticamente no startup
3. **Queue Worker**: Reinicia automaticamente em caso de falha
4. **Variáveis .env**: Todas as configurações podem ser ajustadas no `.env`

---

## 🐛 Troubleshooting

### Container não inicia
```bash
# Ver logs detalhados
docker-compose logs app

# Verificar se há erros no build
docker-compose build --no-cache
```

### Queue Worker não processa jobs
```bash
# Ver logs
docker-compose logs queue-worker

# Reiniciar
docker-compose restart queue-worker
```

### Erro de conexão com MinIO
1. Verificar variáveis no `.env`
2. Testar conexão manualmente (ver Passo 4)
3. Verificar se o endpoint está acessível

---

## ✅ Checklist Final

- [ ] Arquivo `.env` configurado
- [ ] Containers iniciados (`docker-compose ps`)
- [ ] Logs sem erros críticos
- [ ] Queue worker rodando
- [ ] Teste de MinIO bem-sucedido
- [ ] Aplicação acessível em `http://localhost:8000`

---

**Status:** ✅ PRONTO PARA USAR!

**Próximo passo:** Execute `iniciar-docker-minio.bat` ou os comandos manualmente!

















