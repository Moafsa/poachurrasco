# RESOLVER PROBLEMA DE CACHE - PASSO A PASSO

## O Problema

O Laravel **NÃO sincroniza automaticamente**. Você precisa:

1. **Limpar cache do Laravel**
2. **Recompilar assets (Vite)**
3. **Limpar cache do navegador**
4. **Reiniciar o servidor Laravel** (às vezes necessário)

## Solução Rápida (Execute nesta ordem)

### 1. Execute o script de força total:

```bash
.\FORCAR_ATUALIZACAO.bat
```

### 2. REINICIE o servidor Laravel:

**No terminal onde o Laravel está rodando:**
- Pressione `Ctrl + C` para parar
- Execute novamente: `php artisan serve`

### 3. Limpe TUDO no navegador:

1. Pressione `Ctrl + Shift + Delete`
2. Selecione **"Todo o período"**
3. Marque **TUDO** (especialmente "Imagens e arquivos em cache")
4. Clique em "Limpar dados"

### 4. Feche e abra o navegador completamente

### 5. Acesse `localhost:8000` novamente

## Por que isso acontece?

O Laravel tem **múltiplas camadas de cache**:

1. **Views compiladas** → `storage/framework/views/`
2. **Config cache** → `bootstrap/cache/config.php`
3. **Route cache** → `bootstrap/cache/routes-v7.php`
4. **Assets compilados** → `public/build/` (via Vite)
5. **Cache do navegador** → Arquivos CSS/JS antigos

## Verificação

Depois de fazer tudo acima, abra o DevTools (F12) e verifique:

1. **Network tab** → Deve mostrar a requisição da imagem do Unsplash
2. **Elements tab** → Procure por "Capital Mundial do Churrasco" (não "Descubra as melhores...")
3. **Console tab** → Não deve ter erros de carregamento

## Se AINDA não funcionar:

1. **Verifique o arquivo diretamente:**
   - Abra `resources/views/public/home.blade.php`
   - Procure por "Capital Mundial do Churrasco" na linha 163
   - Se não estiver lá, o arquivo foi sobrescrito

2. **Verifique se está na rota correta:**
   - A URL deve ser `localhost:8000/` (raiz)
   - Não `localhost:8000/home` ou outra rota

3. **Verifique o servidor:**
   - O servidor Laravel deve estar rodando
   - Verifique se não há erros no terminal

## Modo Desenvolvimento (Para evitar isso no futuro)

Execute em um terminal separado:

```bash
npm run dev
```

Isso recompila automaticamente quando você salva arquivos.

**MAS** você ainda precisa limpar o cache do Laravel quando mudar views Blade.

## Comandos Manuais (se os scripts não funcionarem)

```bash
# Limpar cache Laravel
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Recompilar assets
.\node_modules\.bin\vite build

# Depois: limpar navegador e reiniciar servidor
```




















