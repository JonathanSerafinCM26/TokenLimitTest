<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\Gemini;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\GeminiTokenCount;

class GeminiTokenTestController extends Controller
{
    public function testTokenLimits()
    {
        try {
            // Limpiar usuarios de prueba anteriores
            User::where('email', 'like', '%@test.com')->delete();

            // 1. Crear usuarios de prueba con diferentes roles y emails únicos
            $timestamp = now()->timestamp;
            $limitedUser = User::create([
                'name' => 'Usuario Limitado',
                'email' => "limited_{$timestamp}@test.com",
                'password' => Hash::make('password123')
            ]);
            
            $premiumUser = User::create([
                'name' => 'Usuario Premium',
                'email' => "premium_{$timestamp}@test.com",
                'password' => Hash::make('password123')
            ]);

            // 2. Asignar roles
            $limitedUser->assignGeminiRole('limited_user');
            $premiumUser->assignGeminiRole('premium_user');

            // 3. Probar el conteo de tokens
            $testPrompt = "Escribe una historia corta sobre un bosque mágico";
            $tokens = GeminiTokenCount::coutTextTokens($testPrompt);

            // 4. Probar permisos de solicitud
            $limitedUserStatus = [
                'can_request' => $limitedUser->canMakeRequestToGemini(),
                'is_active' => $limitedUser->isActiveInGemini()
            ];

            $premiumUserStatus = [
                'can_request' => $premiumUser->canMakeRequestToGemini(),
                'is_active' => $premiumUser->isActiveInGemini()
            ];

            // 5. Probar solicitud real a Gemini y seguimiento de tokens
            $responses = [];
            
            if ($limitedUser->canMakeRequestToGemini()) {
                $gemini = Gemini::newChat();
                $response = $gemini->newPrompt($testPrompt);
                $limitedUser->updateUsageTracking($tokens);
                $limitedUser->storeGeminiRequest(
                    requestType: "Test Story", 
                    consumedTokens: $tokens, 
                    requestSuccessful: true, 
                    requestData: ["prompt" => $testPrompt],
                    responseData: ["response" => $response]
                );
                
                $responses['limited_user_response'] = $response;
            }

            // 6. Retornar resultados de prueba detallados
            return response()->json([
                'success' => true,
                'test_results' => [
                    'users_created' => [
                        'limited_user' => [
                            'id' => $limitedUser->id,
                            'name' => $limitedUser->name,
                            'email' => $limitedUser->email,
                            'status' => $limitedUserStatus
                        ],
                        'premium_user' => [
                            'id' => $premiumUser->id,
                            'name' => $premiumUser->name,
                            'email' => $premiumUser->email,
                            'status' => $premiumUserStatus
                        ]
                    ],
                    'test_prompt' => $testPrompt,
                    'token_count' => $tokens,
                    'gemini_responses' => $responses
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error durante la prueba: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}