# DIAGNÓSTICO COMPLETO - Hero Duplicada

## Passo a Passo para Diagnosticar

### 1. Verificar se o Vite está rodando

**Abra um terminal e execute:**
```bash
npm run dev
```

**Você DEVE ver:**
```
VITE v7.x.x  ready in xxx ms
➜  Local:   http://localhost:5173/
```

**Se NÃO aparecer isso, o problema é que o Vite não está rodando!**

### 2. Verificar o HTML no navegador

1. Abra `localhost:8000` no navegador
2. Pressione F12 (DevTools)
3. Vá na aba **Elements**
4. Procure por `id="hero-section-main"` (deve aparecer APENAS 1 vez)
5. Se aparecer 2 vezes, há duplicação no código
6. Se aparecer 1 vez mas visualmente está duplicado, é problema de CSS

### 3. Verificar requisições do Vite

1. No DevTools, vá na aba **Network**
2. Recarregue a página (F5)
3. Procure por requisições para `localhost:5173`
4. **Se NÃO aparecer nenhuma requisição para 5173, o Laravel não está usando Vite dev!**

### 4. Verificar CSS carregado

1. No DevTools → Network
2. Procure por `app.css` ou `app-*.css`
3. Clique nele
4. Vá na aba **Response**
5. Procure por `.hero-section` ou `hero-background`
6. Se aparecer múltiplas vezes, pode estar duplicado no CSS

### 5. Teste Simples - Adicionar borda vermelha

Adicione isso no final de `resources/css/app.css`:

```css
#hero-section-main {
    border: 10px solid red !important;
}
```

Salve e veja se aparece:
- **1 borda vermelha** = Está funcionando, mas pode ser CSS duplicando visualmente
- **2 bordas vermelhas** = Há 2 elementos hero no HTML (duplicação real)
- **Nenhuma borda** = O CSS não está sendo aplicado (Vite não está rodando)

## Soluções

### Se o Vite não está rodando:

1. Abra um terminal
2. Execute: `npm run dev`
3. Deixe rodando
4. Recarregue a página

### Se há duplicação no HTML:

1. Verifique se há 2 `@section('content')` no arquivo
2. Verifique se há 2 `@extends('layouts.app')`
3. Verifique se o layout não está incluindo a view duas vezes

### Se é problema de CSS:

1. Limpe o cache: `.\limpar-cache.bat`
2. Recompile: `.\node_modules\.bin\vite build`
3. Limpe o navegador: Ctrl+Shift+Delete

## Comando Rápido

Execute este script para verificar tudo:
```bash
.\VERIFICAR_VITE.bat
```






