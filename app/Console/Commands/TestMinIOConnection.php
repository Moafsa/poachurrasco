<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TestMinIOConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'minio:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test MinIO connection and upload functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Testing MinIO Connection ===');
        $this->newLine();

        // 1. Verificar configuração
        $this->info('1. Checking configuration...');
        $endpoint = env('MINIO_ENDPOINT');
        $bucket = env('MINIO_BUCKET');
        $key = env('MINIO_ACCESS_KEY');
        $secret = env('MINIO_SECRET_KEY');
        $region = env('MINIO_REGION', 'us-east-1');
        
        $this->line("   Endpoint: {$endpoint}");
        $this->line("   Bucket: {$bucket}");
        $this->line("   Region: {$region}");
        $this->line("   Access Key: " . substr($key, 0, 10) . '...');
        $this->line("   Secret Key: " . (strlen($secret) > 0 ? substr($secret, 0, 10) . '...' : 'NOT SET'));
        $this->newLine();

        // 2. Testar conexão com o disco
        $this->info('2. Testing disk connection...');
        try {
            $disk = Storage::disk('minio');
            $this->info('   ✓ Disk instance created successfully');
        } catch (\Exception $e) {
            $this->error('   ✗ Failed to create disk instance: ' . $e->getMessage());
            return 1;
        }

        // 3. Testar se consegue listar arquivos (testa conexão)
        $this->info('3. Testing connection (listing files)...');
        try {
            $files = $disk->files('branding');
            $this->info("   ✓ Connection successful! Found " . count($files) . " file(s) in 'branding' directory");
            if (count($files) > 0) {
                $this->line("   Files found:");
                foreach (array_slice($files, 0, 5) as $file) {
                    $this->line("     - {$file}");
                }
                if (count($files) > 5) {
                    $this->line("     ... and " . (count($files) - 5) . " more");
                }
            }
        } catch (\Exception $e) {
            $this->error('   ✗ Connection failed: ' . $e->getMessage());
            $this->error('   Error details: ' . $e->getTraceAsString());
            return 1;
        }
        $this->newLine();

        // 4. Criar arquivo de teste
        $this->info('4. Testing file upload...');
        $testContent = 'This is a test file created at ' . now()->toDateTimeString();
        $testPath = 'branding/test-' . time() . '.txt';
        
        try {
            $disk->put($testPath, $testContent);
            $this->info("   ✓ File uploaded successfully: {$testPath}");
        } catch (\Exception $e) {
            $this->error('   ✗ Upload failed: ' . $e->getMessage());
            $this->error('   Error details: ' . $e->getTraceAsString());
            return 1;
        }
        $this->newLine();

        // 5. Verificar se o arquivo existe
        $this->info('5. Verifying file exists...');
        try {
            if ($disk->exists($testPath)) {
                $this->info("   ✓ File exists: {$testPath}");
            } else {
                $this->error("   ✗ File not found: {$testPath}");
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('   ✗ Error checking file existence: ' . $e->getMessage());
            return 1;
        }
        $this->newLine();

        // 6. Ler o conteúdo do arquivo
        $this->info('6. Reading file content...');
        try {
            $content = $disk->get($testPath);
            if ($content === $testContent) {
                $this->info('   ✓ File content matches');
            } else {
                $this->warn('   ⚠ File content does not match (but file exists)');
            }
        } catch (\Exception $e) {
            $this->error('   ✗ Error reading file: ' . $e->getMessage());
            return 1;
        }
        $this->newLine();

        // 7. Obter URL do arquivo
        $this->info('7. Getting file URL...');
        try {
            $url = $disk->url($testPath);
            $this->info("   ✓ URL generated: {$url}");
        } catch (\Exception $e) {
            $this->error('   ✗ Error generating URL: ' . $e->getMessage());
            $this->error('   Error details: ' . $e->getTraceAsString());
            // Não retorna erro aqui, apenas avisa
        }
        $this->newLine();

        // 8. Deletar arquivo de teste
        $this->info('8. Cleaning up test file...');
        try {
            if ($disk->delete($testPath)) {
                $this->info("   ✓ Test file deleted: {$testPath}");
            } else {
                $this->warn("   ⚠ Could not delete test file (may not exist): {$testPath}");
            }
        } catch (\Exception $e) {
            $this->warn('   ⚠ Error deleting test file: ' . $e->getMessage());
            // Não retorna erro aqui, apenas avisa
        }
        $this->newLine();

        // 9. Testar upload de imagem (simulado)
        $this->info('9. Testing image upload simulation...');
        try {
            // Criar uma imagem PNG simples (1x1 pixel)
            $imageContent = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
            $imagePath = 'branding/test-image-' . time() . '.png';
            
            $disk->put($imagePath, $imageContent);
            $this->info("   ✓ Test image uploaded: {$imagePath}");
            
            // Verificar se existe
            if ($disk->exists($imagePath)) {
                $this->info("   ✓ Test image exists");
                
                // Obter URL
                $imageUrl = $disk->url($imagePath);
                $this->info("   ✓ Image URL: {$imageUrl}");
                
                // Deletar
                $disk->delete($imagePath);
                $this->info("   ✓ Test image deleted");
            } else {
                $this->error("   ✗ Test image not found after upload");
            }
        } catch (\Exception $e) {
            $this->error('   ✗ Image upload test failed: ' . $e->getMessage());
            $this->error('   Error details: ' . $e->getTraceAsString());
            return 1;
        }
        $this->newLine();

        $this->info('=== All tests passed! MinIO is working correctly. ===');
        return 0;
    }
}
