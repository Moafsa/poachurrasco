# PWA Setup - Porto Alegre Capital Mundial do Churrasco

## ✅ O que foi implementado

O sistema agora está configurado como Progressive Web App (PWA) e pode ser instalado em Android, iOS e Desktop.

### Arquivos criados:

1. **`public/manifest.json`** - Manifesto PWA com configurações para todas as plataformas
2. **`public/sw.js`** - Service Worker para cache e funcionamento offline
3. **`resources/js/pwa.js`** - Script de registro do service worker
4. **`public/browserconfig.xml`** - Configuração para Windows/Edge
5. **Meta tags PWA** - Adicionadas no layout principal

## 📱 Como funciona

### Android
- Usuários podem instalar o app através do prompt "Adicionar à tela inicial"
- O app funciona offline após a primeira visita
- Ícones aparecem na tela inicial e na gaveta de apps

### iOS (Safari)
- Usuários podem adicionar à tela inicial através do menu "Compartilhar" > "Adicionar à Tela de Início"
- Funciona como app nativo após instalação
- Suporta splash screen e ícones personalizados

### Desktop (Chrome, Edge, Firefox)
- Usuários podem instalar através do ícone de instalação na barra de endereços
- O app abre em janela própria, sem barra de endereços
- Funciona offline após a primeira visita

## 🎨 Ícones necessários

Para completar a configuração PWA, você precisa criar os seguintes ícones na pasta `public/images/icons/`:

- `icon-72x72.png` (72x72 pixels)
- `icon-96x96.png` (96x96 pixels)
- `icon-128x128.png` (128x128 pixels)
- `icon-144x144.png` (144x144 pixels)
- `icon-152x152.png` (152x152 pixels)
- `icon-192x192.png` (192x192 pixels)
- `icon-384x384.png` (384x384 pixels)
- `icon-512x512.png` (512x512 pixels)

### Como gerar os ícones:

1. **Usando ferramentas online:**
   - [PWA Asset Generator](https://github.com/onderceylan/pwa-asset-generator)
   - [RealFaviconGenerator](https://realfavicongenerator.net/)
   - [PWA Builder](https://www.pwabuilder.com/imageGenerator)

2. **Usando imagem base:**
   - Crie uma imagem quadrada de 512x512 pixels com o logo do projeto
   - Use uma ferramenta de redimensionamento para gerar todos os tamanhos
   - Certifique-se de que os ícones são legíveis em tamanhos pequenos

3. **Requisitos dos ícones:**
   - Formato: PNG
   - Fundo: Transparente ou sólido (recomendado)
   - Tamanho mínimo: 512x512 pixels (para o maior ícone)
   - Tamanho máximo: 512x512 pixels (para o maior ícone)
   - Todos os ícones devem ser quadrados

## 🚀 Como testar

### 1. Desenvolvimento local

```bash
# Certifique-se de que está usando HTTPS (necessário para PWA)
# Use ngrok ou configure SSL local

# Acesse o site
# Abra o DevTools > Application > Service Workers
# Verifique se o service worker está registrado
```

### 2. Testar instalação

**Android (Chrome):**
1. Acesse o site no Chrome
2. Aguarde o prompt "Adicionar à tela inicial" aparecer
3. Ou use o menu do Chrome > "Adicionar à tela inicial"

**iOS (Safari):**
1. Acesse o site no Safari
2. Toque no botão de compartilhar
3. Selecione "Adicionar à Tela de Início"

**Desktop (Chrome/Edge):**
1. Acesse o site
2. Procure pelo ícone de instalação na barra de endereços
3. Clique para instalar

### 3. Testar funcionamento offline

1. Instale o app
2. Abra o DevTools > Network
3. Marque "Offline"
4. Recarregue a página
5. O app deve continuar funcionando com conteúdo em cache

## ⚙️ Configurações importantes

### HTTPS obrigatório

PWAs **só funcionam em HTTPS** (exceto localhost). Certifique-se de que:

- O site está servido via HTTPS em produção
- O certificado SSL é válido
- Não há avisos de segurança no navegador

### Service Worker

O service worker está configurado para:
- Cachear assets estáticos na instalação
- Cachear requisições dinâmicas em runtime
- Funcionar offline
- Atualizar automaticamente quando houver nova versão

### Manifest

O manifest.json inclui:
- Nome e descrição do app
- Ícones para todas as plataformas
- Shortcuts (atalhos) para páginas principais
- Configurações de display (standalone)
- Cores de tema

## 🔧 Personalização

### Alterar cores do tema

Edite `public/manifest.json`:
```json
{
  "theme_color": "#dc2626",  // Cor da barra de status
  "background_color": "#ffffff"  // Cor de fundo do splash screen
}
```

E também em `resources/views/layouts/app.blade.php`:
```html
<meta name="theme-color" content="#dc2626">
```

### Adicionar mais páginas ao cache

Edite `public/sw.js`:
```javascript
const PRECACHE_ASSETS = [
  '/',
  '/mapa',
  '/products',
  // ... adicione mais páginas
];
```

### Personalizar comportamento offline

Edite `public/sw.js` para ajustar a estratégia de cache:
- `cache-first`: Serve do cache primeiro
- `network-first`: Tenta rede primeiro, fallback para cache
- `stale-while-revalidate`: Serve cache enquanto atualiza em background

## 📝 Checklist de deploy

- [ ] Todos os ícones foram criados e estão em `public/images/icons/`
- [ ] Site está servido via HTTPS
- [ ] Service worker está registrado (verificar no DevTools)
- [ ] Manifest.json está acessível em `/manifest.json`
- [ ] Testado em Android (Chrome)
- [ ] Testado em iOS (Safari)
- [ ] Testado em Desktop (Chrome/Edge)
- [ ] Funcionamento offline testado
- [ ] Ícones aparecem corretamente após instalação

## 🐛 Troubleshooting

### Service Worker não registra

- Verifique se está usando HTTPS (ou localhost)
- Verifique o console do navegador para erros
- Certifique-se de que `/sw.js` está acessível

### Ícones não aparecem

- Verifique se os arquivos existem em `public/images/icons/`
- Verifique se os caminhos no `manifest.json` estão corretos
- Limpe o cache do navegador

### App não funciona offline

- Verifique se o service worker está ativo
- Verifique se os assets estão sendo cacheados
- Teste no DevTools > Application > Cache Storage

## 📚 Recursos adicionais

- [MDN - Progressive Web Apps](https://developer.mozilla.org/en-US/docs/Web/Progressive_web_apps)
- [Web.dev - PWA](https://web.dev/progressive-web-apps/)
- [PWA Checklist](https://web.dev/pwa-checklist/)




















