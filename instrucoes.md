BRIEFING TÉCNICO E ROADMAP DE DESENVOLVIMENTO PARA CURSOR IA

PROJETO: PLATAFORMA "PORTO ALEGRE, A CAPITAL MUNDIAL DO CHURRASCO"

1. VISÃO GERAL DO PROJETO

Objetivo: Desenvolver uma plataforma web completa e robusta que sirva como o hub central para a cultura do churrasco em Porto Alegre. O site deve funcionar como um guia turístico, marketplace e portal de conteúdo, conectando estabelecimentos parceiros (churrascarias, boutiques de carne, etc.) a turistas e moradores locais.

Pilares:

Descoberta: Facilitar a busca e o encontro dos melhores locais para churrasco.

Comércio: Permitir que parceiros vendam produtos e serviços diretamente na plataforma.

Conteúdo: Educar e engajar o usuário com a história e a cultura do churrasco gaúcho.

Monetização: Gerar receita através de mensalidades de parceiros, comissões de marketplace e espaços publicitários.

2. ARQUITETURA E TECNOLOGIAS (TECH STACK)

Frontend: Next.js (para performance, SEO e renderização híbrida).

Estilização: Tailwind CSS (para um desenvolvimento de UI rápido e consistente).

Backend & Banco de Dados: Supabase. A escolha se dá por utilizar PostgreSQL como base, oferecendo autenticação, storage e functions serverless, o que se alinha perfeitamente com a necessidade do projeto.

Mapa Interativo: Leaflet.js com tiles do OpenStreetMap (para evitar custos iniciais da API do Google Maps).

Pagamentos: API do Asaas (para gerenciar Pix, boletos, split de pagamentos e assinaturas).

Inteligência Artificial: API do Google Gemini para o assistente de conteúdo dos parceiros.

3. ESTRUTURA DE ARQUIVOS E CONFIGURAÇÃO INICIAL

Setup do Projeto: Inicie um novo projeto Next.js com o template de App Router: npx create-next-app@latest poa-churrasco --typescript --tailwind --eslint.

Estrutura de Pastas:

/
├── /app/
│   ├── /(site)/              // Rotas públicas do site
│   │   ├── /guia/
│   │   │   ├── /[id]/page.tsx // Página de detalhe
│   │   │   └── page.tsx      // Página de listagem
│   │   ├── /mapa/page.tsx
│   │   ├── /marketplace/page.tsx
│   │   └── page.tsx          // Página Inicial (Home)
│   ├── /dashboard/           // Rotas protegidas para parceiros
│   │   ├── /perfil/page.tsx
│   │   ├── /produtos/page.tsx
│   │   └── layout.tsx        // Layout com navegação do dashboard
│   └── layout.tsx
├── /components/
│   ├── /ui/                  // Componentes de UI genéricos (Button, Card, Input, etc.)
│   ├── /shared/              // Componentes compartilhados (Header, Footer)
│   └── /features/            // Componentes específicos (Map, ProductList, ReviewForm)
├── /lib/                     // Funções utilitárias, clientes de API (supabase.ts, asaas.ts)
└── /styles/                  // Estilos globais

4. DESENVOLVIMENTO PASSO A PASSO (PÁGINA A PÁGINA)
PASSO 1: Página Inicial (app/(site)/page.tsx)
Componente: Header.tsx

Deve ser fixo no topo (sticky top-0), com fundo translúcido e efeito backdrop-blur.

Logo à esquerda.

Links de navegação (Guia, Mapa, Marketplace, Roteiros) centralizados e visíveis em desktop.

Botão "Seja um Parceiro" à direita.

Menu hambúrguer funcional para dispositivos móveis.

Seção Hero:

Ocupar a altura da tela (h-screen).

Fundo com imagem de alta qualidade (use placehold.co para placeholders).

Título principal e subtítulo com destaque e animação de entrada.

Barra de busca funcional no centro.

Seção "Estabelecimentos em Destaque":

Título da seção estilizado.

Grid com 3 componentes EstablishmentCard.tsx.

Cada card deve ter: imagem, nome, descrição curta, categoria (tag colorida) e nota de avaliação.

Botão "Ver todos os estabelecimentos" que leva para a página /guia.

Seção "Mapa do Churrasco":

Título e subtítulo.

Uma imagem estática grande representando o mapa interativo.

Botão "Abrir Mapa Completo" que leva para a página /mapa.

Seção "Marketplace do Churrasqueiro":

Título da seção.

Grid com 4 componentes ProductCard.tsx.

Cada card deve ter: imagem, nome do produto, nome do vendedor, preço e botão "Comprar".

Botão "Visitar o Marketplace" que leva para a página /marketplace.

Seção "Roteiros e Experiências":

Título da seção.

Grid com 2 cards grandes e visuais, com imagem de fundo e texto sobreposto.

Cada card leva para a página do roteiro específico.

Componente: Footer.tsx

Logo, links de navegação, ícones de redes sociais e texto de copyright com CNPJ e site da Conext.

PASSO 2: Banco de Dados (Supabase)
Crie as seguintes tabelas no seu projeto Supabase:

profiles (para usuários e parceiros, vinculado ao auth.users)

id (uuid, primary key, foreign key to auth.users.id)

full_name (text)

is_partner (boolean, default false)

establishments (dados dos parceiros)

id (uuid, primary key)

owner_id (uuid, foreign key to profiles.id)

name (text)

description (text)

address (text)

phone (text)

category (enum: 'Churrascaria', 'Boutique de Carnes', 'Bar')

images (array of text urls)

latitude (float)

longitude (float)

products

id (uuid, primary key)

establishment_id (uuid, foreign key to establishments.id)

name (text)

description (text)

price (numeric)

image_url (text)

reviews

id (uuid, primary key)

establishment_id (uuid, foreign key to establishments.id)

user_id (uuid, foreign key to profiles.id)

rating (integer, 1-5)

comment (text)

PASSO 3: Dashboard do Parceiro (app/dashboard/)
Autenticação: Crie a lógica de login e cadastro usando o Supabase Auth. A área /dashboard deve ser protegida e acessível apenas para perfis com is_partner = true.

Página de Perfil: Formulário para o parceiro editar os dados da tabela establishments.

Página de Produtos:

Listagem dos produtos cadastrados (da tabela products).

Formulário para adicionar/editar um produto.

FUNCIONALIDADE IA: Crie um botão "Gerar Descrição com IA". Ao clicar, ele pega o nome do produto e a categoria, envia para uma API externa (Gemini/OpenAI) com um prompt como: "Crie uma descrição de marketing curta e atrativa para o produto '[NOME_DO_PRODUTO]' que é vendido em um(a) '[CATEGORIA]'." e preenche o campo de descrição com a resposta.

Página de Análise: (Feature futura) Mostrar gráficos simples de visualizações de perfil e vendas.

PASSO 4: Integração com Pagamentos (Asaas)
Setup: Configure o cliente da API do Asaas em /lib/asaas.ts.

Fluxo de Pagamento (Marketplace):

Quando o usuário clica em "Comprar", o produto é adicionado a um carrinho de compras (gerenciado no estado do frontend).

No checkout, colete os dados do cliente.

Crie uma cobrança na API do Asaas (via Pix ou Boleto).

Implemente o Split de Pagamentos para que a comissão vá para a conta da Conext e o valor do produto para a conta do parceiro (vendedor).

Fluxo de Assinatura (Parceiros):

Na área "Seja um Parceiro", após o cadastro, crie uma assinatura recorrente no Asaas para a cobrança da mensalidade.

PASSO 5: Finalização e Lançamento
Responsividade: Verifique e ajuste todas as páginas para garantir uma experiência perfeita em todos os tamanhos de tela.

SEO: Utilize os metadados do Next.js em cada página (generateMetadata) para definir títulos e descrições dinâmicas.

Deploy: Faça o deploy do projeto na Vercel, configurando as variáveis de ambiente para as chaves do Supabase e do Asaas.

Este documento serve como guia mestre. Siga a ordem dos passos para um desenvolvimento estruturado e eficiente. Boa codificação!