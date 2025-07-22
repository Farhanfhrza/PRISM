<?php

namespace App\Http\Controllers\Api;

use Exception;
// use Gemini\Client;
use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;
use App\Http\Controllers\Controller;

class GeminiController extends Controller
{
    public function generateText(Request $request)
    {
        // Ambil prompt dari request atau gunakan default
        $prompt = $request->input('prompt', 'Apa itu Laravel?');

        $result = Gemini::generativeModel(model: 'gemini-2.0-flash')->generateContent($prompt);

        return response()->json([
            'prompt' => $prompt,
            'response' => $result->text(),
        ]);
    }

    public function listAvailableModels()
    {
        try {
            // Panggil metode listModels() melalui Facade Gemini
            $models = Gemini::listModels();

            $modelList = [];
            foreach ($models as $model) {
                $modelList[] = [
                    'name' => $model->name,
                    'description' => $model->description,
                    'supported_methods' => $model->supportedGenerationMethods,
                ];
            }

            return response()->json(['available_models' => $modelList]);

        } catch (Exception $e) { // Tangkap Exception umum
            return response()->json([
                'error' => 'Gagal mendapatkan daftar model: ' . $e->getMessage()
            ], 500);
        }
    }
}
