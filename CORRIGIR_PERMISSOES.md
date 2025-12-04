# 🔧 Corrigir Erro de Permissões - Storage Framework Views

## 🐛 O Problema

O erro ocorre quando o Laravel tenta compilar uma view Blade:

```
file_put_contents(/var/www/storage/framework/views/...): Failed to open stream: Permission denied
```

Isso acontece porque o diretório `storage/framework/views` não tem permissões de escrita para o usuário `www-data` que executa o PHP-FPM.

## ✅ Soluções

### Solução 1: Correção Rápida (Recomendada - Não precisa reconstruir)

Execute este comando para corrigir as permissões no container que está rodando:

```bash
docker exec -it poachurras_app bash -c "chown -R www-data:www-data /var/www/storage && chmod -R 775 /var/www/storage && mkdir -p /var/www/storage/framework/views && chmod -R 775 /var/www/storage/framework/views && chown -R www-data:www-data /var/www/storage/framework/views"
```

Ou copie o script `fix-permissions.sh` para o container e execute:

```bash
# Copiar o script para o container
docker cp fix-permissions.sh poachurras_app:/tmp/fix-permissions.sh

# Executar o script dentro do container
docker exec -it poachurras_app bash /tmp/fix-permissions.sh

# Limpar cache do Laravel
docker exec -it poachurras_app php artisan view:clear
```

### Solução 2: Reconstruir o Container (Permanente)

O Dockerfile foi atualizado para corrigir automaticamente as permissões na inicialização. Para aplicar:

```bash
# Parar os containers
docker-compose down

# Reconstruir a imagem
docker-compose build --no-cache app

# Iniciar novamente
docker-compose up -d

# Verificar os logs para confirmar que as permissões foram corrigidas
docker-compose logs app | grep "FIXING PERMISSIONS"
```

### Solução 3: Manual dentro do Container

Se preferir corrigir manualmente:

```bash
# Entrar no container
docker exec -it poachurras_app bash

# Dentro do container, executar:
chown -R www-data:www-data /var/www/storage
chmod -R 775 /var/www/storage
mkdir -p /var/www/storage/framework/views
chmod -R 775 /var/www/storage/framework/views
chown -R www-data:www-data /var/www/storage/framework/views

# Limpar cache de views
php artisan view:clear

# Sair do container
exit
```

## 🔍 Verificação

Após aplicar a correção, verifique se as permissões estão corretas:

```bash
docker exec -it poachurras_app ls -la /var/www/storage/framework/views
```

Você deve ver algo como:
```
drwxrwxr-x 2 www-data www-data 4096 Dec  2 14:43 .
```

O importante é que:
- O proprietário seja `www-data:www-data`
- As permissões sejam `775` (rwxrwxr-x)

## 🚀 Próximos Passos

Depois de corrigir as permissões:

1. Limpe o cache de views:
   ```bash
   docker exec -it poachurras_app php artisan view:clear
   ```

2. Teste acessando a rota que estava dando erro:
   ```
   http://localhost:8000/receitas/guias
   ```

3. Se ainda houver problemas, verifique os logs:
   ```bash
   docker-compose logs app | tail -50
   ```

## 📝 Nota Técnica

O problema ocorre porque:

1. O volume Docker `poachurrasco_data:/var/www/storage` é montado no container
2. Quando o volume é criado pelo Docker, ele pode ter permissões diferentes
3. O PHP-FPM roda como `www-data` e precisa de permissão de escrita
4. O Laravel compila as views Blade e salva em `storage/framework/views`

O Dockerfile foi atualizado para garantir que as permissões sejam corrigidas automaticamente toda vez que o container iniciar.




