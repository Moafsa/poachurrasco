# 🐳 Deploy Automático Docker - Portal do Churrasco Gaúcho

## 🚀 Deploy Automático

O projeto está configurado para **deploy automático** via Docker Compose. Todas as migrations e seeders são executados automaticamente quando o container é criado.

### Comando de Deploy
```bash
# Deploy completo automático
docker-compose up --build -d
```

## 🔧 Configuração Automática

### Dockerfile Atualizado
- **Entrypoint automático** que aguarda o banco estar pronto
- **Execução automática** de migrations
- **Execução automática** de seeders
- **Inicialização** do PHP-FPM

### Docker Compose Atualizado
- **Dependências** configuradas (app aguarda db e redis)
- **Volumes** mapeados corretamente
- **Networks** configurados

## 📊 Estrutura de Deploy

### 1. Build da Imagem
```dockerfile
# Instala dependências PHP
# Instala extensões necessárias
# Configura entrypoint automático
```

### 2. Inicialização Automática Inteligente
```bash
# 1. Aguarda banco estar pronto
# 2. Verifica se é primeira execução
# 3a. PRIMEIRA VEZ: Executa migrations + seeders
# 3b. EXECUÇÕES SEGUINTES: Só executa migrations pendentes
# 4. Preserva dados existentes
# 5. Inicia PHP-FPM
```

### 3. Migrations Executadas Automaticamente
- ✅ Tabelas do sistema original
- ✅ Tabelas do Portal do Churrasco
- ✅ Todas as foreign keys e relacionamentos

### 4. Seeders Executados Automaticamente
- ✅ **Primeira execução**: Usuários de teste, estabelecimentos e guias de exemplo
- ✅ **Execuções seguintes**: Preserva dados existentes, não executa seeders novamente

## 🌐 URLs Após Deploy

- **Aplicação Principal**: http://localhost:8000
- **Portal do Churrasco**: http://localhost:8000/churrasco
- **Guias**: http://localhost:8000/churrasco/guias
- **Chat IA**: http://localhost:8000/churrasco/chat
- **Calculadora**: http://localhost:8000/churrasco/calculadora

## ⚙️ Configuração de Ambiente

### Variáveis Necessárias (.env)
```env
# Database (PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=db
DB_DATABASE=poachurras
DB_USERNAME=poachurras
DB_PASSWORD=your-secure-password-here

# Redis
REDIS_HOST=redis

# OpenAI (para IA Gaúcha)
OPENAI_API_KEY=your-openai-api-key-here

# App
APP_URL=http://localhost:8000
```

## 💾 Preservação de Dados

### ✅ **Dados SEMPRE Preservados**
- **Usuários** cadastrados
- **Estabelecimentos** adicionados
- **Guias de churrasco** criados
- **Avaliações** e comentários
- **Favoritos** dos usuários
- **Conversas** com a IA
- **Qualquer dado** inserido manualmente

### 🔄 **Comportamento Inteligente**
```bash
# PRIMEIRA EXECUÇÃO (banco vazio)
docker-compose up --build -d
# → Executa migrations + seeders (dados de exemplo)

# EXECUÇÕES SEGUINTES (banco com dados)
docker-compose up --build -d
# → Só executa migrations pendentes
# → Preserva TODOS os dados existentes
```

### 📊 **Volumes Persistentes**
- **PostgreSQL**: Volume `postgres_data` mantém dados entre deploys
- **Redis**: Cache preservado entre reinicializações
- **Storage**: Arquivos mantidos no volume mapeado

## 🔍 Verificação do Deploy

### Logs do Container
```bash
# Ver logs do app
docker-compose logs app

# Ver logs em tempo real
docker-compose logs -f app
```

### Verificar Migrations
```bash
# Entrar no container
docker-compose exec app bash

# Verificar migrations executadas
php artisan migrate:status
```

### Verificar Seeders
```bash
# Verificar dados no banco
docker-compose exec db psql -U poachurras -d poachurras -c "SELECT COUNT(*) FROM bbq_guides;"
```

## 🎯 Funcionalidades Disponíveis

### Portal do Churrasco Gaúcho
- ✅ **Guias Completos** - 5 guias de exemplo
- ✅ **IA Gaúcha** - Chat interativo (com OpenAI)
- ✅ **Calculadora** - Quantidades e tempo
- ✅ **Interface Responsiva** - Design moderno

### Sistema Original Mantido
- ✅ **Dashboard** administrativo
- ✅ **Estabelecimentos** - Gestão completa
- ✅ **Avaliações** e favoritos
- ✅ **Google OAuth** integrado
- ✅ **APIs externas** funcionando

## 🚨 Troubleshooting

### Se o Deploy Falhar
```bash
# Limpar containers e volumes
docker-compose down -v

# Rebuild completo
docker-compose up --build --force-recreate
```

### Se as Migrations Falharem
```bash
# Verificar logs
docker-compose logs app

# Entrar no container para debug
docker-compose exec app bash
php artisan migrate:status
```

### Se a IA Não Funcionar
- Verificar se `OPENAI_API_KEY` está configurada
- Verificar logs do container para erros de API
- Testar conectividade com OpenAI

## 📈 Próximos Passos

1. **Configurar OpenAI API** para IA completa
2. **Personalizar conteúdo** dos guias
3. **Adicionar mais funcionalidades** conforme necessário
4. **Configurar domínio** para produção
5. **Implementar SSL** se necessário

---

**🎉 Deploy 100% Automático - Zero Configuração Manual!**
