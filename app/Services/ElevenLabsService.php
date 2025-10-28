<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ElevenLabsService
{
    private string $apiKey;
    private string $voiceId;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('services.elevenlabs.api_key');
        $this->voiceId = config('services.elevenlabs.voice_id');
        $this->model = config('services.elevenlabs.model');
    }

    /**
     * Generate audio from text using ElevenLabs API
     */
    public function generateAudio(string $text): ?string
    {
        if (empty($this->apiKey) || empty($this->voiceId)) {
            Log::warning('ElevenLabs API key or voice ID not configured');
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Accept' => 'audio/mpeg',
                'Content-Type' => 'application/json',
                'xi-api-key' => $this->apiKey,
            ])->post("https://api.elevenlabs.io/v1/text-to-speech/{$this->voiceId}", [
                'text' => $text,
                'model_id' => $this->model,
                'voice_settings' => [
                    'stability' => 0.5,
                    'similarity_boost' => 0.5,
                    'style' => 0.0,
                    'use_speaker_boost' => true
                ]
            ]);

            if ($response->successful()) {
                // Generate unique filename
                $filename = 'audio_' . uniqid() . '.mp3';
                $filePath = 'public/audio/' . $filename;
                
                // Store the audio file
                Storage::put($filePath, $response->body());
                
                // Return the public URL
                return Storage::url($filePath);
            }

            Log::error('ElevenLabs API error', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            return null;

        } catch (\Exception $e) {
            Log::error('ElevenLabs Service error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get available voices from ElevenLabs
     */
    public function getVoices(): array
    {
        if (empty($this->apiKey)) {
            return [];
        }

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'xi-api-key' => $this->apiKey,
            ])->get('https://api.elevenlabs.io/v1/voices');

            if ($response->successful()) {
                $data = $response->json();
                return $data['voices'] ?? [];
            }

            return [];

        } catch (\Exception $e) {
            Log::error('ElevenLabs voices fetch error', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Clean up old audio files
     */
    public function cleanupOldAudioFiles(int $maxAgeHours = 24): void
    {
        try {
            $audioPath = 'public/audio/';
            $files = Storage::files($audioPath);
            
            foreach ($files as $file) {
                $lastModified = Storage::lastModified($file);
                $ageHours = (time() - $lastModified) / 3600;
                
                if ($ageHours > $maxAgeHours) {
                    Storage::delete($file);
                }
            }
        } catch (\Exception $e) {
            Log::error('Audio cleanup error', ['error' => $e->getMessage()]);
        }
    }
}
