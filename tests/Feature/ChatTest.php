<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Models\User;

class ChatTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test chat endpoint returns fallback mock response when API key is missing.
     */
    public function test_chat_returns_mock_response_when_api_key_is_missing(): void
    {
        $user = User::factory()->create();

        // Ensure GEMINI_API_KEY is not defined in config for this test
        config(['services.gemini.api_key' => null]);

        $response = $this->actingAs($user)->postJson(route('chat'), [
            'prompt' => 'Buat soal matematika kelas 5'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'response',
            'is_mock'
        ]);
        $this->assertTrue($response->json('is_mock'));
        $this->assertStringContainsString('Matematika', $response->json('response'));
    }

    /**
     * Test chat endpoint validates required prompt.
     */
    public function test_chat_requires_prompt(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson(route('chat'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['prompt']);
    }

    /**
     * Test chat endpoint contacts Gemini API when API key is present.
     */
    public function test_chat_contacts_gemini_api_when_key_is_present(): void
    {
        $user = User::factory()->create();

        // Mock the services config
        config(['services.gemini.api_key' => 'mock-real-api-key-123']);

        // Mock Http facade
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Ini jawaban tiruan dari Gemini API untuk guru.']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->actingAs($user)->postJson(route('chat'), [
            'prompt' => 'Halo Asisten'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'response' => 'Ini jawaban tiruan dari Gemini API untuk guru.',
            'is_mock' => false
        ]);
    }
}
