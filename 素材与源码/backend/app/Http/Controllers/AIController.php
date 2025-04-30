<?php

namespace App\Http\Controllers;

use App\Contracts\Services\AIServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AIController extends Controller
{
    protected $aiService;

    public function __construct(AIServiceInterface $aiService)
    {
        $this->aiService = $aiService;
        $this->middleware('auth:api');
    }

    public function chat(Request $request)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        try {
            $response = $this->aiService->chat($request->content);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'AI 服务暂时不可用'
            ], 500);
        }
    }

    public function generateLessonPlan(Request $request)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        try {
            $response = $this->aiService->generateLessonPlan($request->all());
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '生成教案失败'
            ], 500);
        }
    }

    public function generateCourseware(Request $request)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        try {
            $response = $this->aiService->generateCourseware($request->all());
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '生成课件失败'
            ], 500);
        }
    }

    public function analyzeArticle(Request $request)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        try {
            $response = $this->aiService->analyzeArticle($request->all());
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '分析文章失败'
            ], 500);
        }
    }

    public function correctEssay(Request $request)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        try {
            $response = $this->aiService->correctEssay($request->all());
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '批改作文失败'
            ], 500);
        }
    }

    public function getChatHistory()
    {
        try {
            $chats = $this->aiService->getChatHistory(Auth::id());
            return response()->json($chats);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '获取聊天历史失败'
            ], 500);
        }
    }

    public function getChat($id)
    {
        try {
            $chat = $this->aiService->getChat($id);
            return response()->json($chat);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '获取聊天记录失败'
            ], 500);
        }
    }

    public function deleteChat($id)
    {
        try {
            $result = $this->aiService->deleteChat($id);
            return response()->json([
                'success' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '删除聊天记录失败'
            ], 500);
        }
    }

    public function exportChat($id)
    {
        try {
            $result = $this->aiService->exportChat($id);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '导出聊天记录失败'
            ], 500);
        }
    }

    public function saveChat(Request $request)
    {
        $request->validate([
            'messages' => 'required|array'
        ]);

        try {
            $chat = $this->aiService->saveChat(Auth::id(), $request->messages);
            return response()->json($chat);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '保存聊天失败'
            ], 500);
        }
    }
} 