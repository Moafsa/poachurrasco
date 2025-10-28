# Configuração do Portal do Churrasco Gaúcho

## Configuração da OpenAI

Para usar a funcionalidade de IA gaúcha, você precisa configurar a API da OpenAI:

### 1. Obter API Key da OpenAI

1. Acesse [https://platform.openai.com/](https://platform.openai.com/)
2. Crie uma conta ou faça login
3. Vá para "API Keys" no menu
4. Clique em "Create new secret key"
5. Copie a chave gerada

### 2. Configurar no Laravel

Adicione as seguintes variáveis ao seu arquivo `.env`:

```env
# OpenAI Configuration
OPENAI_API_KEY=sk-sua-chave-aqui
OPENAI_MODEL=gpt-3.5-turbo
OPENAI_MAX_TOKENS=1000
```

### 3. Executar Migrações e Seeders

```bash
# Executar migrações
php artisan migrate

# Executar seeders para popular com dados de exemplo
php artisan db:seed --class=BbqGuideSeeder
```

## Funcionalidades do Portal

### 1. Guias de Churrasco
- Guias completos com diferentes tipos de carne
- Instruções detalhadas de preparo
- Informações nutricionais
- Dicas e truques

### 2. IA Gaúcha
- Assistente virtual com personalidade gaúcha
- Conhecimento especializado em churrasco
- Respostas em português com sotaque gaúcho
- Histórico de conversas

### 3. Calculadora de Churrasco
- Cálculo de quantidades por pessoa
- Tempo de cocção estimado
- Acompanhamentos sugeridos
- Cronograma de preparo

### 4. Rotas Disponíveis

- `/churrasco` - Página inicial do portal
- `/churrasco/guias` - Lista de guias com filtros
- `/churrasco/guia/{id}` - Detalhes de um guia específico
- `/churrasco/chat` - Chat com IA gaúcha
- `/churrasco/calculadora` - Calculadora de churrasco

## Estrutura do Banco de Dados

### Tabela: bbq_guides
Armazena os guias de churrasco com informações completas sobre:
- Tipo de carne e corte
- Ingredientes e passos
- Tempo de cocção e temperatura
- Dificuldade e porções
- Calorias e dicas

### Tabela: bbq_chats
Armazena as conversas com a IA:
- Mensagens do usuário e respostas da IA
- Contexto da conversa
- Sessões de chat

## Personalização da IA

A IA está configurada com uma personalidade gaúcha autêntica:
- Usa expressões típicas como "bah", "tchê", "tri"
- Conhecimento profundo sobre churrasco
- Respostas didáticas mas descontraídas
- Foco em tradição e qualidade

## Próximos Passos

1. Configure a API da OpenAI
2. Execute as migrações
3. Teste as funcionalidades
4. Personalize os guias conforme necessário
5. Adicione mais conteúdo e funcionalidades

## Suporte

Para dúvidas sobre a implementação, consulte:
- Documentação do Laravel
- Documentação da OpenAI API
- Código fonte dos controllers e services
