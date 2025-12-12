# ✅ Consolidação Docker - MinIO e Notificações

## 📋 Resumo das Alterações

### ✅ Arquivos Modificados

1. **docker-compose.yml**
   - ✅ Variáveis de ambiente MinIO adicionadas
   - ✅ Variáveis de ambiente Mail configuráveis
   - ✅ Serviço `queue-worker` adicionado para processar filas
   - ✅ Storage disk configurável via variável de ambiente

2. **Dockerfile**
   - ✅ Script de inicialização atualizado para incluir `composer dump-autoload`
   - ✅ Garantia de que helpers sejam carregados corretamente

3. **composer.json**
   - ✅ Helper `storage_helper.php` já adicionado ao autoload

---

## 🚀 Comandos para Executar

### 1. Verificar/Criar arquivo .env

Certifique-se de que seu arquivo `.env` contém as seguintes variáveis:

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
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@poachurras.com
MAIL_FROM_NAME="Porto Alegre Capital Mundial do Churrasco"
```

### 2. Reconstruir e Iniciar Containers

```bash
# Reconstruir imagens com as novas configurações
docker-compose build

# Parar containers existentes (se estiverem rodando)
docker-compose down

# Iniciar todos os serviços (incluindo novo queue-worker)
docker-compose up -d

# Ver status de todos os serviços
docker-compose ps
```

### 3. Verificar Logs

```bash
# Ver logs da aplicação principal
docker-compose logs -f app

# Ver logs do queue worker
docker-compose logs -f queue-worker

# Ver logs de todos os serviços
docker-compose logs -f
```

### 4. Executar Comandos Adicionais (Opcional)

```bash
# Recarregar autoload manualmente (já é feito automaticamente no startup)
docker-compose exec app composer dump-autoload --optimize

# Limpar e recriar cache
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:cache

# Testar conexão com MinIO
docker-compose exec app php artisan tinker
# Dentro do tinker:
>>> Storage::disk('minio')->put('test.txt', 'Hello MinIO');
>>> Storage::disk('minio')->exists('test.txt');
>>> Storage::disk('minio')->url('test.txt');
```

---

## 🔍 Verificação Pós-Deploy

### Checklist:

- [ ] Containers iniciados sem erros (`docker-compose ps`)
- [ ] Queue worker rodando (`docker-compose logs queue-worker`)
- [ ] Autoload recarregado (verificar logs: "Autoload dumped!")
- [ ] Teste de upload para MinIO bem-sucedido
- [ ] Teste de envio de notificação bem-sucedido

### Verificar Queue Worker:

```bash
# Verificar se o worker está processando jobs
docker-compose logs queue-worker | grep -i "processed"

# Verificar se há erros
docker-compose logs queue-worker | grep -i error
```

### Verificar MinIO:

```bash
# Executar dentro do container
docker-compose exec app php artisan tinker

# Testar upload
>>> use Illuminate\Support\Facades\Storage;
>>> Storage::disk('minio')->put('test/hello.txt', 'Hello from MinIO');
>>> Storage::disk('minio')->exists('test/hello.txt');
>>> Storage::disk('minio')->url('test/hello.txt');
```

---

## 📊 Estrutura de Serviços Atualizada

```
docker-compose.yml
├── app                    # Aplicação Laravel (Nginx + PHP-FPM)
├── db                     # PostgreSQL
├── redis                  # Redis Cache
├── vite                   # Vite Dev Server
└── queue-worker          # ✨ NOVO - Worker de Filas
    └── Processa fila 'notifications'
    └── 3 tentativas por job
    └── Timeout de 90 segundos
```

---

## 🎯 Próximos Passos

1. **Configurar .env** com as credenciais corretas
2. **Reconstruir containers**: `docker-compose build`
3. **Iniciar serviços**: `docker-compose up -d`
4. **Verificar logs**: `docker-compose logs -f`
5. **Testar funcionalidades**:
   - Upload de arquivo para MinIO
   - Envio de notificação por email

---

## ⚠️ Notas Importantes

1. **Autoload Automático**: O `composer dump-autoload` é executado automaticamente no startup do container
2. **Queue Worker**: O worker reinicia automaticamente em caso de falha
3. **Variáveis de Ambiente**: Todas podem ser sobrescritas pelo `.env`
4. **Primeira Execução**: Pode levar alguns minutos na primeira vez para construir as imagens

---

## 📚 Documentação Relacionada

- `CONFIGURACAO_MINIO_NOTIFICACOES.md` - Configuração detalhada
- `IMPLEMENTACAO_MINIO_NOTIFICACOES.md` - Resumo técnico
- `DOCKER_SETUP_MINIO_NOTIFICACOES.md` - Setup Docker completo

---

**Status:** ✅ Pronto para deploy
**Data:** {{ date('Y-m-d H:i:s') }}

















