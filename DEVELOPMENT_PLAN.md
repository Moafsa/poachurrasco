# POA Capital do Churrasco — Plano de Desenvolvimento Multiagente

Este plano organiza três agentes trabalhando em paralelo no código Laravel. O objetivo é alinhar escopo, sequência e entregáveis para que a plataforma evolua de forma consistente, escalável e sustentável. Integrações com iFood e AiQFome ficam fora deste ciclo conforme solicitação recente.

---

## Princípios Orientadores

- Priorizar soluções simples, coerentes com as convenções atuais do Laravel e sem duplicação.
- Planejar cada entrega considerando ambientes `dev`, `test` e `prod`, com configuração claramente isolada.
- Fatiar o trabalho em incrementos testáveis; cada marco deve vir acompanhado de validação e estratégia de rollback.
- Manter a documentação e cópias de interface em inglês nos arquivos do código-fonte (exceto `estrutura-modulos.md`), anotando sempre que um dado fictício for exclusivo de testes.

---

## Agente 1 — Fundamentos da Plataforma (GPT-5 Codex)

**Missão principal:** fortalecer autenticação, reforçar segurança, elevar a experiência do desenvolvedor e garantir cobertura de testes nos fluxos críticos.

- **Autenticação e Acesso**
  - Substituir o middleware `FakeAuth` pela autenticação Google OAuth (Socialite) com fallback por e-mail/senha.
  - Integrar policies (`EstablishmentPolicy` e correlatas) nas rotas/controladores; validar guards, sessões e throttling.
- **Segurança e Conformidade**
  - Auditar uploads: sanitização de MIME, limites de tamanho, permissões de storage e ganchos para antivírus.
  - Configurar rate limiting e proteção CSRF nas rotas públicas (`/api/establishments/*`, sincronização de reviews etc.).
- **Internacionalização e Consistência**
  - Padronizar textos Blade, mensagens flash e notificações em inglês; avaliar helpers de localização se necessário.
- **Experiência do Desenvolvedor**
  - Implantar pipelines com `phpunit`, Pint e análise estática; criar testes de base para estabelecimentos, reviews, favoritos.
  - Documentar setup de ambientes (`dev`, `testing`, `production`) sem sobrescrever `.env`; gerar `env.example`.
- **Entregáveis e Marcos**
  - Marco A1: Autenticação real em produção com testes cobrindo fluxos principais.
  - Marco A2: Política de uploads e medidas de segurança documentadas.
  - Marco A3: Suite de testes e padrões de código prontos para CI.
- **Dependências:** alinhar com o Agente 2 sobre guards no dashboard e com o Agente 3 sobre endpoints públicos que exigem autenticação real.

---

## Agente 2 — Módulos de Marketplace e Conteúdo

**Missão principal:** transformar os modelos de produtos, promoções, serviços, receitas e vídeos em módulos completos no dashboard, com APIs REST correspondentes.

- **Entrega de CRUDs**
  - Criar controllers, form requests, policies e repositórios conforme necessidade de cada módulo.
  - Produzir componentes Blade reutilizáveis (tabelas, drawers, galerias) e dividir arquivos com mais de 350 linhas.
- **Evolução do Dashboard**
  - Implementar filtros, paginação e widgets de analytics (view count, saúde do estoque) usando os atributos já previstos.
  - Adicionar ações em lote quando fizer sentido (troca de status, uploads múltiplos) mantendo UX enxuta.
- **APIs REST**
  - Expor endpoints autenticados que reflitam as operações do dashboard com paginação, ordenação e busca.
  - Garantir padrão de resposta JSON com `success`, payload e metadados alinhados ao restante do sistema.
- **Testes e Validação**
  - Expandir testes de feature para cobrir CRUD e regras de autorização de cada módulo.
  - Provisionar factories e seeds apenas para uso em testes automatizados.
- **Entregáveis e Marcos**
  - Marco B1: Produtos e promoções entregues end-to-end.
  - Marco B2: Serviços, receitas e vídeos com dashboards e APIs concluídos.
  - Marco B3: Biblioteca de componentes compartilhados + documentação de uso.
- **Dependências:** receber do Agente 1 as políticas de autenticação; fornecer ao Agente 3 as APIs para consumo público.

---

## Agente 3 — Experiência Pública e Serviços Externos

**Missão principal:** evoluir a experiência pública, consolidar reviews internos/externos e preparar recursos operacionais (pedidos, notificações), respeitando as integrações postergadas.

- **Experiência Pública**
  - Implementar landing page dinâmica por slug, busca unificada, mapa interativo, guias de receita e interfaces do chat.
  - Consumir APIs do Agente 2 para exibir produtos, promoções, serviços e conteúdo em tempo real.
- **Reviews e Inteligência**
  - Estender o `ReviewService` com jobs assíncronos, cache e monitoração do mix interno/externo.
  - Criar painéis ou widgets que destaquem rating consolidado e tendências.
- **Pedidos e Favoritos (MVP)**
  - Modelar fluxo básico de pedidos (carrinho, checkout, status) integrado a usuários autenticados com auditoria.
  - Expor favoritos no front público e sincronizar com o dashboard.
- **Notificações e Analytics**
  - Preparar infraestrutura de notificações (e-mail/push) com filas e ganchos configuráveis para provedores futuros.
  - Adotar observabilidade (logs, métricas) e relatórios leves para estabelecimentos acompanharem performance.
- **Entregáveis e Marcos**
  - Marco C1: Portal público totalmente abastecido por dados reais.
  - Marco C2: Jobs de reviews externos rodando com monitoramento.
  - Marco C3: MVP de pedidos/favoritos + base de notificações.
- **Dependências:** utilizar autenticação e políticas do Agente 1, além das APIs fornecidas pelo Agente 2; manter backlog preparado para futuras integrações financeiras/marketplaces.

---

## Coordenação entre Agentes

- Realizar checkpoint semanal para revisar backlog, remover bloqueios e garantir paridade de ambientes.
- Adotar definição de pronto comum: testes documentados, lint rodado, QA manual com notas e checklist de deploy.
- Acompanhar progresso dos capítulos A/B/C em ferramenta de gestão e atualizar este documento quando houver replanejamento.

Este plano orienta os três agentes a trabalharem em paralelo com coesão, elevando a escalabilidade, a manutenibilidade e a prontidão para crescimento da plataforma.


