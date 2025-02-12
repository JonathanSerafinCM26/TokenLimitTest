<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\Gemini;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\GeminiTokenCount;
use Illuminate\Support\Facades\Hash;

class GeminiTokenTestController extends Controller
{
    public function testTokenLimit()
    {
        // Crear usuario de prueba
        $testUser = User::create([
            'name' => 'Test User',
            'email' => 'test_' . time() . '@example.com',
            'password' => Hash::make('password'),
        ]);

        // Asignar rol con límites
        $testUser->assignGeminiRole('limited_user');

        // Array para almacenar resultados
        $results = [];

        // Test 1: Verificar estado inicial
        $results['initial_state'] = [
            'can_make_request' => $testUser->canMakeRequestToGemini(),
            'is_active' => $testUser->isActiveInGemini()
        ];

        // Test 2: Probar conteo de tokens
        $testPrompt = "Escribe una historia corta sobre un gato espacial";
        $tokenCount = GeminiTokenCount::coutTextTokens($testPrompt);
        $results['token_count'] = $tokenCount;

        // Test 3: Hacer una solicitud y actualizar uso
        if ($testUser->canMakeRequestToGemini()) {
            $gemini = Gemini::newChat();
            $response = $gemini->newPrompt($testPrompt);
            $testUser->updateUsageTracking($tokenCount);
            
            // Registrar la solicitud
            $testUser->storeGeminiRequest(
                requestType: "Test",
                consumedTokens: $tokenCount,
                requestSuccessful: true,
                requestData: ["request" => $testPrompt],
                responseData: ["response" => $response]
            );

            $results['request_made'] = true;
            $results['response_received'] = !empty($response);
        } else {
            $results['request_made'] = false;
            $results['error'] = 'Usuario no autorizado para hacer solicitudes';
        }

        // Test 4: Verificar estado después de la solicitud
        $results['final_state'] = [
            'can_make_request' => $testUser->canMakeRequestToGemini(),
            'is_active' => $testUser->isActiveInGemini()
        ];

        return response()->json($results);
    }
}