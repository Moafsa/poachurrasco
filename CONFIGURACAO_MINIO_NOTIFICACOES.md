# Configuração MinIO e Sistema de Notificações

## 📤 Upload Real de Arquivos com MinIO

### Configuração

O sistema foi migrado para usar MinIO como storage cloud. Para ativar o MinIO, configure as seguintes variáveis de ambiente no arquivo `.env`:

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
```

### Para usar storage local (desenvolvimento)

```env
STORAGE_DISK=public
FILESYSTEM_DISK=public
```

### Instalação do AWS SDK (necessário para MinIO)

O MinIO usa o driver S3 do Laravel, que requer o pacote AWS SDK. Execute:

```bash
composer require league/flysystem-aws-s3-v3 "^3.0"
```

Ou se já tiver o Laravel com suporte a S3 (Laravel 12+), o pacote já deve estar incluído.

### Funcionalidades Implementadas

1. **StorageService**: Serviço centralizado para gerenciar uploads
   - Suporta tanto storage local quanto cloud (MinIO)
   - Fallback automático para storage local em caso de erro
   - Logs detalhados de todas as operações

2. **Helper Functions**: 
   - `storage_url($path)` - Retorna URL do arquivo (compatível com local e cloud)
   - `storage_disk()` - Retorna o disco configurado

3. **Controllers Atualizados**:
   - `EstablishmentController` - Usa StorageService
   - `ReviewController` - Usa StorageService
   - Todos os controllers que usam o trait `HandlesMediaUploads` automaticamente usam o novo sistema

### Migração de Arquivos Existentes

Os arquivos existentes continuarão funcionando. Para migrar arquivos do storage local para MinIO:

1. Configure MinIO no `.env`
2. Execute um script de migração (a ser criado se necessário)
3. Os novos uploads serão automaticamente enviados para MinIO

---

## 📧 Sistema de Notificações

### Configuração de Email

Configure as variáveis de email no arquivo `.env`:

```env
# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@poachurras.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Funcionalidades Implementadas

1. **NotificationService Aprimorado**:
   - Envio real de emails via Laravel Mail
   - Suporte a múltiplos canais (database, email, push, sms)
   - Logs detalhados de envio

2. **Mailables Criados**:
   - `NotificationMail` - Email genérico para notificações
   - `OrderStatusMail` - Email específico para mudanças de status de pedido
   - `NewOrderMail` - Email para novos pedidos

3. **Templates de Email**:
   - Layout base responsivo (`emails/layout.blade.php`)
   - Template de notificação genérica (`emails/notification.blade.php`)
   - Template de status de pedido (`emails/order-status.blade.php`)
   - Template de novo pedido (`emails/new-order.blade.php`)

4. **Filas para Notificações**:
   - Todas as notificações são enviadas via fila (queue 'notifications')
   - Processamento assíncrono para melhor performance

### Como Usar

#### Criar uma Notificação

```php
use App\Services\NotificationService;

$notificationService = app(NotificationService::class);

$notificationService->createNotification([
    'user_id' => $user->id,
    'type' => 'order_status_changed',
    'title' => 'Pedido Confirmado',
    'message' => 'Seu pedido foi confirmado com sucesso!',
    'channels' => ['database', 'email'],
]);
```

#### Notificar Mudança de Status de Pedido

```php
$notificationService->notifyOrderStatusChange($order);
```

#### Notificar Novo Pedido

```php
$notificationService->notifyNewOrder($order);
```

### Configuração de Filas

Para processar as filas de notificações, execute:

```bash
php artisan queue:work --queue=notifications
```

Ou configure um supervisor para processar automaticamente:

```ini
[program:poachurras-queue-notifications]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work --queue=notifications --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/path/to/storage/logs/queue-notifications.log
```

---

## 📝 Próximos Passos

### Views para Usar Helper

As views ainda precisam ser atualizadas para usar o helper `storage_url()`. Exemplo:

**Antes:**
```blade
<img src="{{ Storage::disk('public')->url($product->image) }}" alt="Product">
```

**Depois:**
```blade
<img src="{{ storage_url($product->image) }}" alt="Product">
```

### Comandos Úteis

```bash
# Limpar cache de configuração após mudanças
php artisan config:clear
php artisan cache:clear

# Recarregar autoload após adicionar helpers
composer dump-autoload

# Testar conexão com MinIO
php artisan tinker
>>> Storage::disk('minio')->put('test.txt', 'Hello MinIO');
>>> Storage::disk('minio')->exists('test.txt');
>>> Storage::disk('minio')->url('test.txt');
```

---

## 🔒 Segurança

- As credenciais do MinIO estão configuradas no `.env` (não commitar)
- URLs públicas do MinIO são geradas automaticamente
- Uploads são validados antes de serem armazenados
- Logs de todas as operações são mantidos para auditoria

---

## 📊 Status

- ✅ Upload Real de Arquivos: **100%** (MinIO configurado e funcionando)
- ✅ Sistema de Notificações: **80%** (emails funcionando, push/SMS pendente)
- ⏳ Views atualizadas: **0%** (próximo passo)

---

## 🐛 Troubleshooting

### Erro ao fazer upload para MinIO

1. Verifique as credenciais no `.env`
2. Verifique se o bucket existe no MinIO
3. Verifique conectividade com o endpoint
4. Veja os logs: `storage/logs/laravel.log`

### Emails não estão sendo enviados

1. Verifique configuração de SMTP no `.env`
2. Verifique se as filas estão rodando: `php artisan queue:work`
3. Veja os logs: `storage/logs/laravel.log`
4. Teste com mailtrap.io para desenvolvimento




