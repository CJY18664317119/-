<?php

namespace App\Http\Controllers;

use App\Contracts\Services\CollaborationServiceInterface;
use App\Http\Requests\CollaborationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CollaborationController extends Controller
{
    protected $service;

    public function __construct(CollaborationServiceInterface $service)
    {
        $this->service = $service;
    }

    // ... 现有方法保持不变 ...

    /**
     * 获取用户的历史记录
     */
    public function getUserHistory(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $limit = $request->input('limit', 10);
            $result = $this->service->getUserHistory($userId, $limit);
            return response()->json(['data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 搜索历史记录
     */
    public function searchHistory(int $id, Request $request): JsonResponse
    {
        try {
            $keyword = $request->input('keyword');
            $limit = $request->input('limit', 10);
            $result = $this->service->searchHistory($id, $keyword, $limit);
            return response()->json(['data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 按动作类型获取历史记录
     */
    public function getHistoryByAction(int $id, Request $request): JsonResponse
    {
        try {
            $action = $request->input('action');
            $limit = $request->input('limit', 10);
            $result = $this->service->getHistoryByAction($id, $action, $limit);
            return response()->json(['data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 获取历史记录统计
     */
    public function getHistoryStatistics(int $id): JsonResponse
    {
        try {
            $result = $this->service->getHistoryStatistics($id);
            return response()->json(['data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 导出历史记录
     */
    public function exportHistory(int $id): JsonResponse
    {
        try {
            $result = $this->service->exportHistory($id);
            return response()->json(['data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 清理历史记录
     */
    public function cleanupHistory(Request $request): JsonResponse
    {
        try {
            $days = $request->input('days', 30);
            $result = $this->service->cleanupHistory($days);
            return response()->json(['deleted_count' => $result]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
} 