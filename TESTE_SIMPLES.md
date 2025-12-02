# TESTE SIMPLES - Verificar se está funcionando

## Teste 1: Verificar se o Vite está rodando

Abra um terminal e execute:
```bash
npm run dev
```

Você deve ver algo como:
```
VITE v7.x.x  ready in xxx ms

➜  Local:   http://localhost:5173/
➜  Network: use --host to expose
```

**Se não aparecer isso, o Vite não está rodando!**

## Teste 2: Verificar se o servidor Laravel está usando Vite

1. Abra o DevTools (F12)
2. Vá na aba **Network**
3. Recarregue a página
4. Procure por requisições para `localhost:5173` (porta do Vite)

**Se não aparecer requisições para a porta 5173, o Laravel não está usando o Vite em modo dev!**

## Teste 3: Fazer uma mudança visível

1. Abra `resources/css/app.css`
2. Adicione no final:
```css
body {
    border: 10px solid red !important;
}
```

3. Salve o arquivo
4. **Se aparecer uma borda vermelha no navegador automaticamente, está funcionando!**
5. **Se não aparecer, o Vite não está rodando ou não está conectado!**

## Teste 4: Verificar o HTML gerado

1. Abra o DevTools (F12)
2. Vá na aba **Elements**
3. Procure por `<link rel="stylesheet"` no `<head>`
4. **Se o href apontar para `localhost:5173`, está usando Vite dev!**
5. **Se apontar para `/build/assets/`, está usando arquivos compilados!**

## Solução se não estiver funcionando

Se os testes mostrarem que não está usando Vite dev:

1. **Pare o servidor Laravel** (Ctrl+C)
2. **Execute `npm run dev`** em um terminal separado
3. **Aguarde aparecer "ready"**
4. **Execute o servidor Laravel novamente** (`php artisan serve`)
5. **Acesse `localhost:8000`**



