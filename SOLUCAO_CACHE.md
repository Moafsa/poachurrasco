# Solução para Cache - Por que as mudanças não aparecem

## O Problema

Você está vendo conteúdo antigo porque há **múltiplas camadas de cache**:

1. **Cache do Laravel** (views compiladas)
2. **Cache do navegador** (arquivos CSS/JS antigos)
3. **Cache do Vite** (assets compilados antigos)

## Solução Completa (Execute nesta ordem)

### Passo 1: Limpar Cache do Laravel

Execute no terminal (ou use `limpar-cache.bat`):
```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

**OU** execute o script:
```bash
.\limpar-cache.bat
```

### Passo 2: Recompilar Assets

Execute:
```bash
.\build-assets.bat
```

**OU**:
```bash
.\node_modules\.bin\vite build
```

### Passo 3: Limpar Cache do Navegador

**No Chrome/Edge:**
1. Pressione `Ctrl + Shift + Delete`
2. Selecione "Imagens e arquivos em cache"
3. Clique em "Limpar dados"
4. OU simplesmente pressione `Ctrl + F5` na página

**Forçar recarregamento:**
- `Ctrl + F5` = Recarregar ignorando cache
- `Ctrl + Shift + R` = Mesma coisa

### Passo 4: Verificar se está funcionando

1. Abra o DevTools (F12)
2. Vá na aba "Network"
3. Marque "Disable cache"
4. Recarregue a página

## Por que isso acontece?

O Laravel compila e cacheia:
- **Views Blade** → `storage/framework/views/`
- **Config** → `bootstrap/cache/config.php`
- **Assets** → `public/build/` (via Vite)

O navegador também cacheia:
- CSS/JS compilados
- Imagens
- Outros recursos estáticos

## Modo Desenvolvimento (Recomendado)

Para evitar esse problema no futuro, use o modo desenvolvimento:

```bash
npm run dev
```

Isso:
- Recompila automaticamente quando você salva
- Atualiza o navegador automaticamente
- Não precisa limpar cache manualmente

**Deixe esse comando rodando em um terminal separado enquanto desenvolve!**

## Checklist Rápido

Quando fizer mudanças e não aparecer:

- [ ] Execute `.\limpar-cache.bat`
- [ ] Execute `.\build-assets.bat`
- [ ] Pressione `Ctrl + F5` no navegador
- [ ] Se ainda não aparecer, feche e abra o navegador

## Verificação

Para verificar se o arquivo está correto, abra:
- `resources/views/public/home.blade.php`

O código deve ter:
- ✅ "Capital Mundial do Churrasco" (não "Descubra as melhores...")
- ✅ Formulário de busca com input e botão
- ✅ Cards de métricas
- ✅ Imagem de Porto Alegre do Unsplash

Se o código está correto mas não aparece, é **100% problema de cache**.



















