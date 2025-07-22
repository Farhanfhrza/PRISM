<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;

class GeminiController extends Controller
{
    public function generateText(Request $request)
    {
        $prompt = $request->input('prompt', 'Apa itu Laravel?');

        $result = Gemini::generativeModel(model: 'gemini-2.0-flash')->generateContent('$prompt');

        return response()->json([
            'prompt' => $prompt,
            'response' => $result->text(),
        ]);
    }
}
