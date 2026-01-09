# Como Compilar Assets no Laravel

## O Problema

O Laravel usa **Vite** para compilar CSS e JavaScript. Quando você faz mudanças nos arquivos em `resources/css/` ou `resources/js/`, essas mudanças **NÃO aparecem automaticamente** no navegador porque:

1. O Laravel serve os arquivos **compilados** de `public/build/`
2. As mudanças em `resources/` precisam ser **compiladas** primeiro
3. O navegador está usando os arquivos antigos compilados

## Soluções

### Opção 1: Modo Desenvolvimento (Recomendado para desenvolvimento)

Execute o servidor Vite que fica observando mudanças e recompila automaticamente:

```bash
npm run dev
```

Ou use o script:
```bash
.\dev-assets.bat
```

**Vantagens:**
- Recompila automaticamente quando você salva arquivos
- Hot reload (recarrega a página automaticamente)
- Mais rápido para desenvolvimento

**Desvantagem:**
- Precisa deixar o terminal aberto

### Opção 2: Compilar Manualmente (Para produção ou quando não usar dev)

Compile os assets uma vez:

```bash
npm run build
```

Ou use o script:
```bash
.\build-assets.bat
```

**Quando usar:**
- Quando terminar de fazer mudanças e quiser testar
- Antes de fazer deploy
- Quando não quiser deixar o terminal aberto

## Fluxo de Trabalho Recomendado

### Durante Desenvolvimento:
1. Abra um terminal e execute: `npm run dev` (ou `.\dev-assets.bat`)
2. Deixe esse terminal aberto
3. Faça suas mudanças normalmente
4. As mudanças aparecerão automaticamente no navegador

### Antes de Testar/Deploy:
1. Execute: `npm run build` (ou `.\build-assets.bat`)
2. Recarregue a página no navegador (Ctrl+F5 para forçar)

## Troubleshooting

### As mudanças ainda não aparecem?

1. **Limpe o cache do navegador:**
   - Pressione `Ctrl + Shift + Delete`
   - Ou `Ctrl + F5` para forçar recarregamento

2. **Verifique se o build foi executado:**
   - Confira se existe `public/build/manifest.json`
   - Se não existir, execute `npm run build`

3. **Verifique se está em modo desenvolvimento:**
   - Se estiver rodando `npm run dev`, o servidor Vite precisa estar ativo
   - Verifique se há erros no terminal

### Erro: "vite não é reconhecido"

Execute primeiro:
```bash
npm install
```

## Resumo

- **Desenvolvimento ativo:** `npm run dev` (deixe rodando)
- **Compilar uma vez:** `npm run build`
- **Scripts Windows:** Use `.\dev-assets.bat` ou `.\build-assets.bat`




















