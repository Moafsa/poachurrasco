# 🎉 POA Capital Mundial do Churrasco - Setup Completo

## 📋 Pré-requisitos

- Docker Desktop instalado e rodando
- Git instalado
- Portas 8000, 5432 e 6379 livres

## 🚀 Iniciando o Projeto

### 1. Estrutura do Projeto

O projeto já possui os seguintes arquivos configurados:
- ✅ `docker-compose.yml` - Configuração dos containers
- ✅ `Dockerfile` - Imagem PHP-FPM
- ✅ `composer.json` - Dependências PHP
- ✅ `package.json` - Dependências Node.js
- ✅ `tailwind.config.js` - Configuração Tailwind CSS

### 2. Instalação do Laravel

Execute os seguintes comandos:

```bash
# Instalar dependências PHP
composer install

# Copiar arquivo de ambiente
copy .env.example .env

# Gerar chave da aplicação
php artisan key:generate
```

### 3. Configuração do Banco de Dados

Edite o arquivo `.env` e configure:

```env
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=poachurras
DB_USERNAME=poachurras_user
DB_PASSWORD=poachurras_password
```

### 4. Subir os Containers

```bash
# Construir e iniciar os containers
docker-compose up -d --build

# Ver os logs
docker-compose logs -f

# Acessar o container da aplicação
docker-compose exec app bash
```

### 5. Dentro do Container

Execute os seguintes comandos dentro do container:

```bash
# Criar as tabelas do banco
php artisan migrate

# Criar dados de exemplo (seed)
php artisan db:seed

# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Criar link simbólico para storage
php artisan storage:link
```

### 6. Instalar Dependências do Frontend

```bash
# Dentro do container ou localmente
npm install
npm run build

# Para desenvolvimento (hot reload)
npm run dev
```

### 7. Acessar a Aplicação

- **Frontend**: http://localhost:8000
- **Banco de Dados**: localhost:5432
- **Redis**: localhost:6379

## 📂 Estrutura do Projeto

```
poachurras/
├── app/                    # Lógica da aplicação
│   ├── Http/
│   │   ├── Controllers/    # Controladores
│   │   └── Middleware/     # Middlewares
│   ├── Models/             # Modelos Eloquent
│   └── Services/           # Serviços de negócio
├── config/                 # Arquivos de configuração
├── database/
│   ├── migrations/         # Migrations do banco
│   └── seeders/           # Dados iniciais
├── resources/
│   ├── views/             # Templates Blade
│   │   ├── auth/          # Páginas de autenticação
│   │   ├── dashboard/     # Dashboard do parceiro
│   │   └── public/        # Páginas públicas
│   ├── css/               # Estilos Tailwind
│   └── js/                # JavaScript
├── routes/                 # Rotas da aplicação
└── public/                 # Arquivos públicos
```

## 🎨 Design System

### Cores

O projeto usa a paleta de cores "Churrasco" (Laranja):

- **churrasco-50**: #fff7ed (Lightest)
- **churrasco-500**: #f97316 (Main - Primary)
- **churrasco-900**: #7c2d12 (Darkest)

### Fontes

- **Principal**: Poppins (Google Fonts)
- **Fallback**: Figtree

## 🔧 Comandos Úteis

### Docker

```bash
# Iniciar containers
docker-compose up -d

# Parar containers
docker-compose down

# Ver logs
docker-compose logs -f app

# Reiniciar containers
docker-compose restart

# Remover tudo (incluindo volumes)
docker-compose down -v
```

### Laravel

```bash
# Criar migration
php artisan make:migration create_table_name

# Rodar migrations
php artisan migrate

# Rollback
php artisan migrate:rollback

# Criar controller
php artisan make:controller NomeController

# Criar model
php artisan make:model NomeModel

# Limpar cache
php artisan optimize:clear
```

### Frontend

```bash
# Instalar dependências
npm install

# Build para produção
npm run build

# Modo desenvolvimento (hot reload)
npm run dev

# Verificar código
npm run lint
```

## 🔐 Configurações Importantes

### Google OAuth

Adicione no `.env`:

```env
GOOGLE_CLIENT_ID=seu-client-id
GOOGLE_CLIENT_SECRET=seu-secret
```

Configure no Google Cloud Console:
- Tipo: Web application
- URI de redirecionamento: http://localhost:8000/auth/google/callback

### Asaas Payment Gateway

```env
ASAAS_API_KEY=sua-chave-api
ASAAS_API_URL=https://www.asaas.com/api/v3
```

## 📱 Módulos Implementados

### Dashboard do Parceiro

1. ✅ **Gestão de Estabelecimentos** (`/dashboard/establishments`)
   - CRUD completo
   - Upload de mídia
   - Landing page personalizada

2. ✅ **Gestão de Produtos** (`/dashboard/products`)
   - CRUD com imagens
   - Sistema de categorias
   - Controle de estoque

3. ✅ **Gestão de Promoções** (`/dashboard/promotions`)
   - Descontos e códigos
   - Período de validade
   - Controle de usos

4. ✅ **Gestão de Serviços** (`/dashboard/services`)
   - Tipos de serviços
   - Duração e capacidade
   - Preços

5. ✅ **Sistema de Receitas** (`/dashboard/recipes`)
   - Ingredientes
   - Instruções passo a passo
   - Categorias

6. ✅ **Sistema de Vídeos** (`/dashboard/videos`)
   - Player integrado
   - Tags e categorias
   - Thumbnails

### Área Pública

1. ⏳ Home Page
2. ⏳ Mapa Interativo
3. ⏳ Marketplace
4. ⏳ Landing Pages dos Estabelecimentos

## 🚀 Deploy para Coolify

### Preparação

1. Commitar o código para um repositório Git
2. Configurar variáveis de ambiente no Coolify
3. Configurar domínio

### Variáveis de Ambiente no Coolify

Adicione todas as variáveis do `.env.example` no painel do Coolify.

### Build e Deploy

O Coolify vai automaticamente:
1. Clonar o repositório
2. Construir a imagem Docker
3. Executar `composer install`
4. Executar migrations
5. Configurar Nginx

## 🐛 Troubleshooting

### Problemas Comuns

**Container não inicia:**
```bash
# Ver logs
docker-compose logs app

# Reconstruir
docker-compose up -d --build --force-recreate
```

**Erro de permissões:**
```bash
# Dentro do container
chown -R www-data:www-data /var/www/storage
chmod -R 775 /var/www/storage
```

**Banco de dados não conecta:**
```bash
# Verificar se o container está rodando
docker-compose ps

# Ver logs do banco
docker-compose logs db
```

## 📞 Suporte

Para dúvidas ou problemas, entre em contato com o time de desenvolvimento.

---

**Desenvolvido com ❤️ pela equipe Conext**
