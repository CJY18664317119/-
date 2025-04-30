<?php

namespace App\Services;

use App\Models\AIChat;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $apiKey;
    protected $apiEndpoint;

    public function __construct()
    {
        $this->apiKey = config('services.ai.api_key');
        $this->apiEndpoint = config('services.ai.endpoint');
    }

    public function chat($content)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->apiEndpoint . '/chat', [
                'content' => $content
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('AI API Error', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            throw new \Exception('AI service error');
        } catch (\Exception $e) {
            Log::error('AI Service Error', [
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function generateLessonPlan($data)
    {
        return $this->chat([
            'type' => 'lesson_plan',
            'data' => $data
        ]);
    }

    public function generateCourseware($data)
    {
        return $this->chat([
            'type' => 'courseware',
            'data' => $data
        ]);
    }

    public function analyzeArticle($data)
    {
        return $this->chat([
            'type' => 'article_analysis',
            'data' => $data
        ]);
    }

    public function correctEssay($data)
    {
        return $this->chat([
            'type' => 'essay_correction',
            'data' => $data
        ]);
    }

    public function saveChat($userId, $messages)
    {
        $chat = AIChat::create([
            'user_id' => $userId,
            'title' => $this->generateChatTitle($messages),
            'messages' => $messages,
            'last_message_at' => now()
        ]);

        return $chat;
    }

    public function getChatHistory($userId, $limit = 10)
    {
        return AIChat::where('user_id', $userId)
            ->orderBy('last_message_at', 'desc')
            ->limit($limit)
            ->get();
    }

    protected function generateChatTitle($messages)
    {
        $firstMessage = $messages[0]['content'] ?? '';
        return mb_substr($firstMessage, 0, 30) . '...';
    }
} 