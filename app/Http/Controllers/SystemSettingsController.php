<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SystemSettingsController extends Controller
{
    /**
     * Display system settings page
     */
    public function index()
    {
        $showExternal = SystemSetting::showExternalEstablishments();
        
        return view('dashboard.system-settings', [
            'showExternalEstablishments' => $showExternal,
        ]);
    }

    /**
     * Update system settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'show_external_establishments' => 'boolean',
        ]);

        try {
            SystemSetting::set(
                'show_external_establishments',
                $request->boolean('show_external_establishments', true),
                'boolean',
                'Control whether external establishments from Google Places are shown on public pages'
            );

            return redirect()
                ->route('dashboard.system-settings')
                ->with('success', 'Configurações atualizadas com sucesso!');
        } catch (\Exception $e) {
            Log::error('Error updating system settings: ' . $e->getMessage());
            
            return redirect()
                ->route('dashboard.system-settings')
                ->with('error', 'Erro ao atualizar configurações. Tente novamente.');
        }
    }

    /**
     * Get system settings as JSON (for API)
     */
    public function getSettings()
    {
        return response()->json([
            'success' => true,
            'settings' => [
                'show_external_establishments' => SystemSetting::showExternalEstablishments(),
            ],
        ]);
    }
}



