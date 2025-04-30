<?php

namespace App\Http\Controllers;

use App\Contracts\Services\ProjectScenarioServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProjectScenarioController extends Controller
{
    protected $service;

    public function __construct(ProjectScenarioServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * 创建新的项目情境
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            $result = $this->service->createScenario($data);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 获取单个项目情境
     */
    public function get(int $id): JsonResponse
    {
        try {
            $result = $this->service->getScenario($id);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 获取用户的项目情境列表
     */
    public function getUserScenarios(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $limit = $request->input('limit', 10);
            $result = $this->service->getUserScenarios($userId, $limit);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 更新项目情境
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $data = $request->all();
            $result = $this->service->updateScenario($id, $data);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 删除项目情境
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $result = $this->service->deleteScenario($id);
            return response()->json(['success' => $result]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 搜索项目情境
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $keyword = $request->input('keyword', '');
            $limit = $request->input('limit', 10);
            $result = $this->service->searchScenarios($userId, $keyword, $limit);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 获取项目情境模板
     */
    public function getTemplates(Request $request): JsonResponse
    {
        try {
            $limit = $request->input('limit', 10);
            $result = $this->service->getTemplates($limit);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 复制项目情境
     */
    public function duplicate(Request $request, int $id): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $result = $this->service->duplicateScenario($id, $userId);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 更新项目情境状态
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        try {
            $status = $request->input('status');
            $result = $this->service->updateScenarioStatus($id, $status);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 生成项目情境建议
     */
    public function generateSuggestions(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            $result = $this->service->generateSuggestions($data);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 导出项目情境
     */
    public function export(int $id): JsonResponse
    {
        try {
            $result = $this->service->exportScenario($id);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 导入项目情境
     */
    public function import(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $data = $request->all();
            $result = $this->service->importScenario($data, $userId);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 生成项目背景
     */
    public function generateBackground(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            $result = $this->service->generateBackground($data);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 生成项目任务
     */
    public function generateTasks(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            $result = $this->service->generateTasks($data);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 生成项目资源
     */
    public function generateResources(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            $result = $this->service->generateResources($data);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 生成项目评估
     */
    public function generateAssessment(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            $result = $this->service->generateAssessment($data);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
} 