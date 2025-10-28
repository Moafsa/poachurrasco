# 🚀 Deploy no Coolify - POA Churrasco

## ✅ Sistema Pronto para Deploy

O projeto está **100% preparado** para deploy no Coolify com todas as configurações de segurança implementadas.

## 🔧 Configuração no Coolify

### 1. **Repositório GitHub**
- **URL**: `https://github.com/Moafsa/poachurrasco`
- **Branch**: `master`
- **Dockerfile**: Presente na raiz do projeto

### 2. **Variáveis de Ambiente Obrigatórias**

Configure estas variáveis no painel do Coolify:

```env
# Application
APP_NAME="POA Churrasco"
APP_ENV=production
APP_KEY=base64:your-generated-app-key-here
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database (PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=poachurras
DB_USERNAME=poachurras
DB_PASSWORD=your-secure-database-password

# Cache & Session (Redis)
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# Google Services
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_PLACES_API_KEY=your-google-places-api-key
GOOGLE_MAPS_API_KEY=your-google-maps-api-key

# OpenAI
OPENAI_API_KEY=your-openai-api-key
OPENAI_MODEL=gpt-3.5-turbo
OPENAI_MAX_TOKENS=1000

# ElevenLabs
ELEVENLABS_API_KEY=your-elevenlabs-api-key
ELEVENLABS_VOICE_ID=your-voice-id
ELEVENLABS_MODEL=eleven_multilingual_v2

# Optional Services
FOURSQUARE_API_KEY=your-foursquare-api-key
SLACK_BOT_USER_OAUTH_TOKEN=your-slack-token
POSTMARK_TOKEN=your-postmark-token
RESEND_KEY=your-resend-key
```

### 3. **Serviços Necessários**

No Coolify, configure:

#### **PostgreSQL Database**
- **Nome**: `postgres`
- **Versão**: `15-alpine`
- **Variáveis**:
  - `POSTGRES_DB=poachurras`
  - `POSTGRES_USER=poachurras`
  - `POSTGRES_PASSWORD=your-secure-password`

#### **Redis Cache**
- **Nome**: `redis`
- **Versão**: `7-alpine`
- **Porta**: `6379`

### 4. **Configuração do Dockerfile**

O Dockerfile está otimizado para produção e inclui:

- ✅ **Entrypoint automático** que aguarda dependências
- ✅ **Migrações automáticas** na primeira execução
- ✅ **Seeders inteligentes** (só executam se necessário)
- ✅ **Cache otimizado** para produção
- ✅ **Sincronização automática** de avaliações externas
- ✅ **Cron jobs** configurados

### 5. **Portas e Networking**

- **Aplicação**: Porta `9000` (PHP-FPM)
- **Nginx**: Porta `80` (se usar nginx separado)
- **PostgreSQL**: Porta `5432`
- **Redis**: Porta `6379`

### 6. **Volumes Persistentes**

Configure volumes para:
- **PostgreSQL**: `/var/lib/postgresql/data`
- **Storage**: `/var/www/storage`
- **Logs**: `/var/log`

## 🔒 Segurança Implementada

- ✅ **Todas as credenciais** em variáveis de ambiente
- ✅ **Nenhuma informação sensível** no código
- ✅ **Validação e sanitização** de dados
- ✅ **Rate limiting** nas APIs externas
- ✅ **Logs de auditoria** implementados

## 🚀 Processo de Deploy

1. **Clone do repositório** ✅
2. **Build da imagem Docker** ✅
3. **Configuração de serviços** (PostgreSQL + Redis)
4. **Configuração de variáveis** de ambiente
5. **Deploy automático** com migrações
6. **Verificação de funcionamento**

## 📊 Funcionalidades Disponíveis

### Portal do Churrasco Gaúcho
- ✅ **Guias Completos** - 5 guias de técnicas gaúchas
- ✅ **IA Gaúcha** - Chat interativo com OpenAI
- ✅ **Calculadora** - Cálculo de quantidades e tempo
- ✅ **Áudio** - Geração com ElevenLabs

### Sistema de Estabelecimentos
- ✅ **Integração Google Places** - Busca automática
- ✅ **Mapa Interativo** - Visualização com filtros
- ✅ **Sistema de Favoritos** - Salvar preferidos
- ✅ **Avaliações Integradas** - Reviews internas + externas

### Dashboard Administrativo
- ✅ **Gestão Completa** - CRUD de estabelecimentos
- ✅ **Sincronização** - Atualização automática com APIs
- ✅ **Relatórios** - Estatísticas e métricas
- ✅ **Usuários** - Gestão de contas

## 🔍 Verificação Pós-Deploy

Após o deploy, verifique:

1. **Aplicação funcionando**: `https://your-domain.com`
2. **Portal do Churrasco**: `https://your-domain.com/churrasco`
3. **Dashboard**: `https://your-domain.com/dashboard`
4. **APIs externas** funcionando
5. **Banco de dados** conectado
6. **Cache Redis** funcionando

## 📚 Documentação Disponível

- [SECURITY.md](SECURITY.md) - Diretrizes de segurança
- [CONFIGURACAO_APIS_EXTERNAS.md](CONFIGURACAO_APIS_EXTERNAS.md) - APIs externas
- [CONFIGURACAO_ELEVENLABS.md](CONFIGURACAO_ELEVENLABS.md) - Configuração ElevenLabs
- [CONFIGURAR_GOOGLE_MAPS.md](CONFIGURAR_GOOGLE_MAPS.md) - Google Maps
- [CONFIGURAR_GOOGLE_OAUTH.md](CONFIGURAR_GOOGLE_OAUTH.md) - Google OAuth
- [SISTEMA_AVALIACOES_INTEGRADAS.md](SISTEMA_AVALIACOES_INTEGRADAS.md) - Sistema de avaliações

## 🆘 Suporte

Em caso de problemas:
1. Verifique os logs do container
2. Confirme as variáveis de ambiente
3. Teste a conectividade com APIs externas
4. Consulte a documentação específica

---

**🎉 Sistema 100% pronto para deploy no Coolify!**
