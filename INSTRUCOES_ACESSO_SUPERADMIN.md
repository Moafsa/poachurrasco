# 🔐 Instruções de Acesso ao Super Admin

## ✅ Super Admin Criado com Sucesso!

O super admin foi criado no banco de dados. Use as credenciais abaixo para fazer login.

## 📋 Credenciais de Acesso

- **Email**: `admin@poachurras.com`
- **Senha**: `admin123`
- **Role**: `admin`
- **Status**: Ativo ✅

## 🚀 Como Acessar o Painel Super Admin

### Passo 1: Acesse a Página de Login

Abra seu navegador e acesse:
```
http://localhost:8000/login
```

### Passo 2: Faça Login

1. Digite o email: `admin@poachurras.com`
2. Digite a senha: `admin123`
3. Clique em "Sign in"

### Passo 3: Acesse o Painel Super Admin

Após fazer login, você será redirecionado para o dashboard. Para acessar o painel super admin:

**URL Direta**: `http://localhost:8000/super-admin`

Ou clique no botão "Super Admin" que aparece no dashboard (se você tiver permissões de admin).

## 🎯 Funcionalidades do Super Admin

O painel super admin (`/super-admin`) permite gerenciar:

1. **Hero Sections** - Seções hero do site
   - Criar, editar e deletar hero sections
   - Gerenciar mídia das hero sections
   - Organizar ordem das imagens/vídeos

2. **Site Content** - Conteúdo do site
   - Gerenciar conteúdo de páginas
   - Editar textos, HTML e imagens
   - Organizar conteúdo por página e seção

## 🔒 Segurança

⚠️ **IMPORTANTE**: 
- Altere a senha padrão após o primeiro acesso!
- A senha atual (`admin123`) é apenas para desenvolvimento
- Em produção, use uma senha forte e única

## 🛠️ Comandos Úteis

### Criar um Novo Super Admin (se necessário)

Se precisar criar outro super admin, use o comando:

```bash
docker-compose exec app php artisan admin:create
```

Ou com opções diretas:

```bash
docker-compose exec app php artisan admin:create --email=novo@email.com --name="Novo Admin" --password=novaSenha123
```

### Verificar Super Admins Existentes

```bash
docker-compose exec app php artisan tinker --execute="App\Models\User::where('role', 'admin')->get(['email', 'name']);"
```

### Atualizar Senha de um Admin

Você pode atualizar a senha através do código ou criar um comando personalizado. Por enquanto, é recomendado fazer login e alterar a senha pela interface (se essa funcionalidade existir) ou criar um novo admin com a senha desejada.

## 📝 Rotas do Super Admin

Todas as rotas do super admin estão protegidas pelo middleware `['auth', 'admin']`:

- `GET /super-admin` - Dashboard principal
- `GET /super-admin/content` - Gerenciar conteúdo do site
- `POST /super-admin/content` - Salvar conteúdo
- `GET /super-admin/hero-sections` - Listar hero sections
- `GET /super-admin/hero-section/create` - Criar nova hero section
- `GET /super-admin/hero-section/{id}/edit` - Editar hero section

## ✅ Status

- ✅ Containers Docker estão rodando
- ✅ Super Admin criado no banco de dados
- ✅ Usuário está ativo e com role 'admin'
- ✅ Senha configurada: `admin123`

## 🎉 Próximos Passos

1. Acesse `http://localhost:8000/login`
2. Faça login com as credenciais acima
3. Navegue para `http://localhost:8000/super-admin`
4. Comece a gerenciar o conteúdo do site!

---

**Última atualização**: $(Get-Date -Format "dd/MM/yyyy HH:mm")



