# Configuração do ElevenLabs para Geração de Áudio

## Variáveis de Ambiente Necessárias

Adicione as seguintes variáveis ao seu arquivo `.env`:

```env
# ElevenLabs Configuration
# Obtenha sua chave API em: https://elevenlabs.io/
ELEVENLABS_API_KEY=your-elevenlabs-api-key-here

# ID da Voz do ElevenLabs
# Você pode encontrar IDs de voz no seu painel do ElevenLabs
# Exemplos de vozes:
# - Português Brasileiro Feminino: pNInz6obpgDQGcFmaJgB
# - Português Brasileiro Masculino: onwK4e9ZLuTAKqWW03F9
ELEVENLABS_VOICE_ID=your-voice-id-here

# Modelo do ElevenLabs (opcional, padrão: eleven_multilingual_v2)
ELEVENLABS_MODEL=eleven_multilingual_v2
```

## Como Obter as Credenciais

### 1. API Key do ElevenLabs
1. Acesse [https://elevenlabs.io/](https://elevenlabs.io/)
2. Crie uma conta ou faça login
3. Vá para "Profile" → "API Keys"
4. Gere uma nova chave API
5. Copie a chave e cole no `.env` como `ELEVENLABS_API_KEY`

### 2. Voice ID
1. No painel do ElevenLabs, vá para "Voices"
2. Escolha uma voz que fale português brasileiro
3. Copie o Voice ID da voz escolhida
4. Cole no `.env` como `ELEVENLABS_VOICE_ID`

## Vozes Recomendadas para Português Brasileiro

- **Voz Feminina**: pNInz6obpgDQGcFmaJgB
- **Voz Masculina**: onwK4e9ZLuTAKqWW03F9

## Comandos Úteis

### Limpeza de Arquivos de Áudio
```bash
php artisan audio:cleanup
```

### Limpeza com Tempo Personalizado
```bash
php artisan audio:cleanup --hours=12
```

## Funcionalidades Implementadas

1. **Geração Automática de Áudio**: Toda resposta da IA é convertida em áudio
2. **Player de Áudio**: Interface web com controles de reprodução
3. **Limpeza Automática**: Comando para remover arquivos antigos
4. **Formatação Melhorada**: Quebras de linha adequadas no chat
5. **Linguagem Neutra**: IA não assume gênero do usuário

## Estrutura de Arquivos

- `app/Services/ElevenLabsService.php` - Serviço principal do ElevenLabs
- `app/Services/BbqAiService.php` - Serviço de IA atualizado
- `app/Console/Commands/CleanupAudioFiles.php` - Comando de limpeza
- `storage/app/public/audio/` - Diretório para arquivos de áudio

## Troubleshooting

### Erro de API Key
- Verifique se a chave está correta no `.env`
- Confirme se a conta tem créditos disponíveis

### Erro de Voice ID
- Verifique se o ID da voz está correto
- Confirme se a voz suporta português brasileiro

### Arquivos de Áudio Não Aparecem
- Verifique as permissões do diretório `storage/app/public/audio/`
- Execute `php artisan storage:link` se necessário
