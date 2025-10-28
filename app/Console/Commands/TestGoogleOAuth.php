<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Socialite\Facades\Socialite;

class TestGoogleOAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:test-oauth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Google OAuth configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Testando configuração do Google OAuth...');
        $this->newLine();

        // Check environment variables
        $this->info('📋 Variáveis de Ambiente:');
        $this->line('APP_URL: ' . config('app.url'));
        $this->line('GOOGLE_CLIENT_ID: ' . (config('services.google.client_id') ? '✅ Configurado' : '❌ Não configurado'));
        $this->line('GOOGLE_CLIENT_SECRET: ' . (config('services.google.client_secret') ? '✅ Configurado' : '❌ Não configurado'));
        $this->line('GOOGLE_REDIRECT: ' . config('services.google.redirect'));
        $this->newLine();

        // Test redirect URL generation
        $this->info('🔗 Testando URL de Redirecionamento:');
        try {
            $redirectUrl = Socialite::driver('google')->redirect()->getTargetUrl();
            $this->line('URL gerada: ' . $redirectUrl);
            
            // Parse URL to check parameters
            $parsedUrl = parse_url($redirectUrl);
            parse_str($parsedUrl['query'] ?? '', $queryParams);
            
            $this->newLine();
            $this->info('📊 Parâmetros da URL:');
            foreach ($queryParams as $key => $value) {
                $this->line("  {$key}: {$value}");
            }
            
            // Check for redirect_uri parameter
            if (isset($queryParams['redirect_uri'])) {
                $this->line('');
                $this->info('✅ redirect_uri encontrado: ' . $queryParams['redirect_uri']);
            } else {
                $this->line('');
                $this->error('❌ redirect_uri NÃO encontrado na URL!');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Erro ao gerar URL de redirecionamento: ' . $e->getMessage());
        }

        $this->newLine();
        $this->info('📝 Instruções:');
        $this->line('1. Verifique se o redirect_uri acima está configurado no Google Cloud Console');
        $this->line('2. A URI deve ser EXATAMENTE: ' . config('services.google.redirect'));
        $this->line('3. Aguarde alguns minutos após salvar no Google Console');
        $this->line('4. Teste o login novamente');
    }
}