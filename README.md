# 🥩 POA Churrasco - Portal do Churrasco Gaúcho

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<p align="center">
  <strong>Portal completo para amantes do churrasco gaúcho em Porto Alegre</strong>
</p>

## 🚀 Sobre o Projeto

O POA Churrasco é uma plataforma completa desenvolvida em Laravel que oferece:

- **🎯 Guias de Churrasco**: Tutoriais completos sobre técnicas gaúchas
- **🤖 IA Gaúcha**: Assistente inteligente para dúvidas sobre churrasco
- **📍 Mapa Interativo**: Localização de estabelecimentos em Porto Alegre
- **⭐ Sistema de Avaliações**: Reviews integradas com Google Places
- **🔐 Autenticação Google**: Login seguro e rápido
- **📱 Interface Responsiva**: Design moderno e mobile-friendly

## 🛠️ Tecnologias Utilizadas

- **Backend**: Laravel 10, PHP 8.2
- **Frontend**: Blade Templates, Tailwind CSS, JavaScript
- **Banco de Dados**: PostgreSQL
- **Cache**: Redis
- **Containerização**: Docker & Docker Compose
- **APIs Externas**: Google Places, Google Maps, OpenAI, ElevenLabs

## 🔒 Segurança

Este projeto segue rigorosas práticas de segurança:

- ✅ Todas as credenciais são armazenadas em variáveis de ambiente
- ✅ Nenhuma informação sensível no código fonte
- ✅ Arquivo `.env` protegido pelo `.gitignore`
- ✅ Validação e sanitização de dados
- ✅ Rate limiting nas APIs externas

**📋 Consulte [SECURITY.md](SECURITY.md) para diretrizes completas de segurança.**

## 🚀 Deploy Rápido

### Pré-requisitos
- Docker e Docker Compose instalados
- Chaves de API configuradas (Google, OpenAI, ElevenLabs)

### Instalação
```bash
# Clone o repositório
git clone https://github.com/Moafsa/poachurrasco.git
cd poachurrasco

# Configure as variáveis de ambiente
cp .env.example .env
# Edite o .env com suas credenciais

# Deploy automático
docker-compose up --build -d
```

### Acesso
- **Aplicação**: http://localhost:8000
- **Portal do Churrasco**: http://localhost:8000/churrasco
- **Dashboard**: http://localhost:8000/dashboard

## 📚 Documentação

- [Configuração de APIs Externas](CONFIGURACAO_APIS_EXTERNAS.md)
- [Configuração ElevenLabs](CONFIGURACAO_ELEVENLABS.md)
- [Configuração Google Maps](CONFIGURAR_GOOGLE_MAPS.md)
- [Configuração Google OAuth](CONFIGURAR_GOOGLE_OAUTH.md)
- [Sistema de Avaliações](SISTEMA_AVALIACOES_INTEGRADAS.md)
- [Deploy Automático](DEPLOY_DOCKER_AUTOMATICO.md)

## 🔧 Funcionalidades Principais

### Portal do Churrasco
- **Guias Completos**: 5 guias detalhados sobre técnicas gaúchas
- **IA Gaúcha**: Chat interativo com OpenAI para dúvidas
- **Calculadora**: Cálculo de quantidades e tempo de cocção
- **Áudio**: Geração de áudio com ElevenLabs

### Sistema de Estabelecimentos
- **Integração Google Places**: Busca automática de estabelecimentos
- **Mapa Interativo**: Visualização com filtros avançados
- **Sistema de Favoritos**: Salvar estabelecimentos preferidos
- **Avaliações Integradas**: Reviews internas + externas

### Dashboard Administrativo
- **Gestão Completa**: CRUD de estabelecimentos
- **Sincronização**: Atualização automática com APIs externas
- **Relatórios**: Estatísticas e métricas
- **Usuários**: Gestão de contas e permissões

## 🎯 Próximos Passos

- [ ] Implementar sistema de notificações
- [ ] Adicionar mais fontes de dados (TripAdvisor, Yelp)
- [ ] Implementar sistema de cupons e promoções
- [ ] Adicionar funcionalidades de geolocalização
- [ ] Implementar sistema de reservas

## 🤝 Contribuição

Contribuições são bem-vindas! Por favor:

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está licenciado sob a [MIT License](LICENSE).

## 🆘 Suporte

Para suporte ou dúvidas:
- Abra uma [Issue](https://github.com/Moafsa/poachurrasco/issues)
- Consulte a documentação disponível
- Verifique o arquivo [SECURITY.md](SECURITY.md) para questões de segurança

---

**Desenvolvido com ❤️ para a comunidade gaúcha do churrasco!**
