# 🐳 Configuração do Vite no Docker

## ✅ O que foi configurado

O Docker Compose agora inclui um serviço **Vite** que executa automaticamente em modo desenvolvimento:

- ✅ Serviço `vite` adicionado ao `docker-compose.yml`
- ✅ Porta 5173 exposta para o host
- ✅ Hot reload configurado para funcionar com Docker
- ✅ Volumes montados para sincronização de arquivos

## 🚀 Como usar

### 1. Subir os containers

```bash
docker-compose up -d --build
```

Isso irá iniciar:
- `poachurras_app` - Aplicação Laravel (porta 8000)
- `poachurras_db` - Banco de dados PostgreSQL (porta 5434)
- `poachurras_redis` - Redis (porta 6379)
- `poachurras_vite` - Servidor Vite (porta 5173) ⭐ **NOVO**

### 2. Verificar se o Vite está rodando

```bash
docker-compose logs vite
```

Você deve ver algo como:
```
VITE v7.x.x  ready in xxx ms
➜  Local:   http://localhost:5173/
```

### 3. Acessar a aplicação

Abra no navegador: **http://localhost:8000**

Os estilos devem aparecer automaticamente! 🎨

## 🔄 Hot Reload

Agora funciona automaticamente:

1. Edite `resources/css/app.css` ou `resources/js/app.js`
2. Salve o arquivo
3. A página recarrega automaticamente no navegador ✨

## 📋 Comandos úteis

### Ver logs do Vite
```bash
docker-compose logs -f vite
```

### Reiniciar apenas o Vite
```bash
docker-compose restart vite
```

### Parar todos os containers
```bash
docker-compose down
```

### Parar e remover volumes (limpeza completa)
```bash
docker-compose down -v
```

## 🐛 Troubleshooting

### Vite não está carregando os estilos?

1. Verifique se o container está rodando:
   ```bash
   docker-compose ps
   ```

2. Verifique os logs:
   ```bash
   docker-compose logs vite
   ```

3. Verifique se a porta 5173 está acessível:
   ```bash
   curl http://localhost:5173
   ```

### Erro: "Cannot find module"

Execute dentro do container:
```bash
docker-compose exec vite npm install
```

### Mudanças não aparecem?

1. Verifique se os volumes estão montados corretamente
2. Limpe o cache do navegador (Ctrl+F5)
3. Reinicie o container Vite:
   ```bash
   docker-compose restart vite
   ```

## 📝 Arquivos modificados

- `docker-compose.yml` - Adicionado serviço `vite`
- `vite.config.js` - Configurado para aceitar conexões do Docker

## 🎯 Diferença entre Dev e Prod

### Desenvolvimento (Docker)
- Vite roda em container separado
- Hot reload automático
- Mudanças aparecem instantaneamente

### Produção
- Assets compilados com `npm run build` no Dockerfile
- Arquivos estáticos servidos pelo Nginx
- Sem hot reload (não necessário)

## 💡 Dica

Se você quiser desenvolver **sem Docker**, ainda pode usar:
```bash
npm run dev
```

Mas com Docker, tudo funciona automaticamente! 🚀



