# 📋 Plano de Implementação - Melhorias do Carrinho e Checkout

## 🎯 Objetivo
Implementar melhorias de escalabilidade, performance e manutenibilidade no sistema de carrinho e checkout.

---

## 📦 Fase 1: Carrinho Persistente no Banco de Dados

### 1.1 Criar Migration e Model
- ✅ Migration `create_carts_table`
- ✅ Model `Cart` com relacionamentos
- ✅ Migration `create_cart_items_table`
- ✅ Model `CartItem`

### 1.2 Atualizar CartController
- ✅ Detectar se usuário está autenticado
- ✅ Usar banco de dados para usuários autenticados
- ✅ Manter sessão para usuários não autenticados
- ✅ Sincronizar sessão → banco quando usuário faz login

### 1.3 Atualizar JavaScript
- ✅ Detectar autenticação
- ✅ Sincronizar carrinho ao fazer login
- ✅ Usar endpoints apropriados

**Tempo estimado:** 2-3 horas

---

## ⚡ Fase 2: Cache para Produtos

### 2.1 Implementar Cache no ProductController
- ✅ Cachear listagem de produtos
- ✅ Cachear produtos individuais
- ✅ Invalidar cache ao atualizar produtos
- ✅ Tags de cache para categorias

### 2.2 Cache no CartController
- ✅ Cachear informações de produtos ao carregar carrinho
- ✅ Cachear cálculos de totais

**Tempo estimado:** 1-2 horas

---

## 🛡️ Fase 3: Rate Limiting

### 3.1 Configurar Rate Limiting
- ✅ Adicionar rate limits nas rotas do carrinho
- ✅ Configurar diferentes limites por endpoint
- ✅ Mensagens de erro apropriadas

**Tempo estimado:** 1 hora

---

## 🧪 Fase 4: Testes Automatizados

### 4.1 Testes do Carrinho
- ✅ Testes de adicionar produto
- ✅ Testes de remover produto
- ✅ Testes de atualizar quantidade
- ✅ Testes de cálculo de totais
- ✅ Testes de sincronização sessão/banco

### 4.2 Testes do Checkout
- ✅ Testes de criação de pedido
- ✅ Testes de validação de dados
- ✅ Testes de cálculo de totais
- ✅ Testes de aplicação de promoções

**Tempo estimado:** 3-4 horas

---

## 🔄 Fase 5: Filas para Processamento de Pedidos

### 5.1 Criar Jobs
- ✅ `ProcessOrderJob` - Processar pedido
- ✅ `SendOrderConfirmationJob` - Enviar confirmação
- ✅ `UpdateInventoryJob` - Atualizar estoque
- ✅ `NotifyEstablishmentJob` - Notificar estabelecimento

### 5.2 Atualizar OrderController
- ✅ Despachar jobs ao criar pedido
- ✅ Processar em background
- ✅ Tratamento de erros e retry

**Tempo estimado:** 2-3 horas

---

## 📊 Resumo de Implementação

| Fase | Descrição | Status | Tempo |
|------|-----------|--------|-------|
| 1 | Carrinho Persistente | 🔄 Em Progresso | 2-3h |
| 2 | Cache de Produtos | ⏳ Pendente | 1-2h |
| 3 | Rate Limiting | ⏳ Pendente | 1h |
| 4 | Testes Automatizados | ⏳ Pendente | 3-4h |
| 5 | Filas para Pedidos | ⏳ Pendente | 2-3h |

**Total estimado:** 9-13 horas

---

## 🚀 Ordem de Implementação Recomendada

1. **Fase 1** - Carrinho Persistente (maior impacto na UX)
2. **Fase 2** - Cache (melhora performance imediata)
3. **Fase 3** - Rate Limiting (proteção)
4. **Fase 5** - Filas (escalabilidade)
5. **Fase 4** - Testes (garantia de qualidade)

---

## ✅ Critérios de Sucesso

- [ ] Usuários autenticados têm carrinho persistente entre dispositivos
- [ ] Produtos são cacheados e performance melhorada
- [ ] APIs protegidas contra abuso
- [ ] Cobertura de testes > 80%
- [ ] Pedidos processados em background sem bloquear usuário


















