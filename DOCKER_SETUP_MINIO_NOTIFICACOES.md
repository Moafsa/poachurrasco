# 🐳 Configuração Docker - MinIO e Notificações

## ✅ Alterações Realizadas

### 1. **docker-compose.yml**

#### Variáveis de Ambiente Adicionadas:

**MinIO:**
- `STORAGE_DISK` - Define o disco de storage (public/minio)
- `MINIO_ACCESS_KEY` - Chave de acesso MinIO
- `MINIO_SECRET_KEY` - Chave secreta MinIO
- `MINIO_BUCKET` - Nome do bucket
- `MINIO_ENDPOINT` - URL do endpoint MinIO
- `MINIO_URL` - URL pública do MinIO
- `MINIO_REGION` - Região (padrão: us-east-1)
- `MINIO_USE_PATH_STYLE` - Usar path-style endpoint

**Mail:**
- Todas as variáveis de email agora suportam variáveis de ambiente do `.env`

**Storage:**
- `FILESYSTEM_DISK` e `STORAGE_DISK` agora usam variáveis de ambiente

#### Novo Serviço Adicionado:

**queue-worker:**
- Worker dedicado para processar filas de notificações
- Processa fila `notifications`
- Configurado com retry (3 tentativas)
- Timeout de 90 segundos
- Sleep de 3 segundos entre jobs

### 2. **Dockerfile**

#### Atualização no Script de Inicialização:

Adicionado `composer dump-autoload --optimize` antes de otimizar o cache para garantir que o helper `storage_helper.php` seja carregado corretamente.

---

## 📋 Como Usar

### 1. Configurar Variáveis de Ambiente

Crie ou atualize seu arquivo `.env`:

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

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@poachurras.com
MAIL_FROM_NAME="Porto Alegre Capital Mundial do Churrasco"
```

### 2. Reconstruir e Iniciar Containers

```bash
# Reconstruir imagens
docker-compose build

# Iniciar todos os serviços (incluindo queue-worker)
docker-compose up -d

# Ver logs do queue worker
docker-compose logs -f queue-worker

# Ver logs da aplicação
docker-compose logs -f app
```

### 3. Verificar Status dos Serviços

```bash
# Listar containers rodando
docker-compose ps

# Verificar logs de todos os serviços
docker-compose logs
```

---

## 🔧 Comandos Úteis

### Queue Worker

```bash
# Reiniciar queue worker
docker-compose restart queue-worker

# Ver logs do queue worker em tempo real
docker-compose logs -f queue-worker

# Executar comandos dentro do container do queue worker
docker-compose exec queue-worker php artisan queue:work --queue=notifications
```

### Aplicação

```bash
# Executar composer dump-autoload manualmente
docker-compose exec app composer dump-autoload --optimize

# Limpar cache
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear

# Verificar configuração do storage
docker-compose exec app php artisan tinker
>>> Storage::disk('minio')->put('test.txt', 'Hello MinIO');
```

---

## 📊 Estrutura de Serviços

```
docker-compose.yml
├── app (Aplicação Laravel)
│   ├── Nginx
│   ├── PHP-FPM
│   └── Cron Jobs
├── db (PostgreSQL)
├── redis (Redis)
├── vite (Vite Dev Server)
└── queue-worker (Queue Worker para Notificações) ✨ NOVO
```

---

## ⚠️ Importante

1. **Primeira Execução**: Após adicionar o helper, o autoload será recriado automaticamente no startup
2. **Queue Worker**: O worker de fila está configurado para reiniciar automaticamente em caso de falha
3. **Variáveis de Ambiente**: Todas as configurações podem ser sobrescritas pelo arquivo `.env`
4. **Logs**: Os logs do queue worker podem ser visualizados com `docker-compose logs -f queue-worker`

---

## 🐛 Troubleshooting

### Queue Worker não está processando jobs

```bash
# Verificar se o container está rodando
docker-compose ps queue-worker

# Ver logs de erro
docker-compose logs queue-worker

# Reiniciar o worker
docker-compose restart queue-worker
```

### Autoload não está carregando o helper

```bash
# Executar dump-autoload manualmente
docker-compose exec app composer dump-autoload --optimize

# Limpar cache de configuração
docker-compose exec app php artisan config:clear
```

### Erro de conexão com MinIO

1. Verificar variáveis de ambiente no `.env`
2. Testar conexão manualmente:
```bash
docker-compose exec app php artisan tinker
>>> Storage::disk('minio')->put('test.txt', 'Test');
```

---

## ✅ Checklist de Deploy

- [ ] Variáveis de ambiente configuradas no `.env`
- [ ] Imagens Docker reconstruídas (`docker-compose build`)
- [ ] Containers iniciados (`docker-compose up -d`)
- [ ] Queue worker rodando (`docker-compose ps`)
- [ ] Teste de upload para MinIO realizado
- [ ] Teste de envio de notificação realizado
- [ ] Logs verificados (sem erros)

---

**Última atualização:** {{ date('Y-m-d H:i:s') }}

















