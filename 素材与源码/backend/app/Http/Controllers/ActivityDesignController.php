<?php

namespace App\Http\Controllers;

use App\Contracts\Services\ActivityDesignServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ActivityDesignController extends Controller
{
    protected $service;

    public function __construct(ActivityDesignServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * 创建活动设计
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'subject' => 'required|string|max:100',
                'grade' => 'required|string|max:50',
                'duration' => 'required|integer|min:1',
                'objectives' => 'required|array',
                'objectives.*' => 'string',
                'materials' => 'nullable|array',
                'materials.*' => 'string',
                'preparation' => 'nullable|array',
                'preparation.*' => 'string',
                'warm_up' => 'nullable|array',
                'warm_up.*' => 'string',
                'presentation' => 'nullable|array',
                'presentation.*' => 'string',
                'practice' => 'nullable|array',
                'practice.*' => 'string',
                'production' => 'nullable|array',
                'production.*' => 'string',
                'assessment' => 'nullable|array',
                'assessment.*' => 'string',
                'notes' => 'nullable|array',
                'notes.*' => 'string',
                'status' => 'nullable|string|in:draft,published,archived'
            ]);

            $data['user_id'] = $request->user()->id;
            $design = $this->service->createDesign($data);

            return response()->json([
                'success' => true,
                'data' => $design
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 获取活动设计详情
     */
    public function show(int $id): JsonResponse
    {
        try {
            $design = $this->service->getDesign($id);
            return response()->json([
                'success' => true,
                'data' => $design
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * 获取用户的活动设计列表
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $limit = $request->input('limit', 10);
            $designs = $this->service->getUserDesigns($request->user()->id, $limit);
            
            return response()->json([
                'success' => true,
                'data' => $designs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 更新活动设计
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'title' => 'sometimes|string|max:255',
                'subject' => 'sometimes|string|max:100',
                'grade' => 'sometimes|string|max:50',
                'duration' => 'sometimes|integer|min:1',
                'objectives' => 'sometimes|array',
                'objectives.*' => 'string',
                'materials' => 'nullable|array',
                'materials.*' => 'string',
                'preparation' => 'nullable|array',
                'preparation.*' => 'string',
                'warm_up' => 'nullable|array',
                'warm_up.*' => 'string',
                'presentation' => 'nullable|array',
                'presentation.*' => 'string',
                'practice' => 'nullable|array',
                'practice.*' => 'string',
                'production' => 'nullable|array',
                'production.*' => 'string',
                'assessment' => 'nullable|array',
                'assessment.*' => 'string',
                'notes' => 'nullable|array',
                'notes.*' => 'string',
                'status' => 'sometimes|string|in:draft,published,archived'
            ]);

            $design = $this->service->updateDesign($id, $data);
            
            return response()->json([
                'success' => true,
                'data' => $design
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 删除活动设计
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $success = $this->service->deleteDesign($id);
            
            return response()->json([
                'success' => $success,
                'message' => $success ? '活动设计已删除' : '删除失败'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 搜索活动设计
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $keyword = $request->input('keyword', '');
            $limit = $request->input('limit', 10);
            
            $designs = $this->service->searchDesigns($request->user()->id, $keyword, $limit);
            
            return response()->json([
                'success' => true,
                'data' => $designs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 获取活动设计模板
     */
    public function templates(Request $request): JsonResponse
    {
        try {
            $limit = $request->input('limit', 10);
            $templates = $this->service->getTemplates($limit);
            
            return response()->json([
                'success' => true,
                'data' => $templates
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 复制活动设计
     */
    public function duplicate(int $id): JsonResponse
    {
        try {
            $design = $this->service->duplicateDesign($id, request()->user()->id);
            
            return response()->json([
                'success' => true,
                'data' => $design
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 更新活动设计状态
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'status' => 'required|string|in:draft,published,archived'
            ]);

            $design = $this->service->updateDesignStatus($id, $data['status']);
            
            return response()->json([
                'success' => true,
                'data' => $design
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 生成活动设计建议
     */
    public function generateSuggestions(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'subject' => 'required|string|max:100',
                'grade' => 'required|string|max:50',
                'duration' => 'required|integer|min:1',
                'objectives' => 'required|array',
                'objectives.*' => 'string'
            ]);

            $suggestions = $this->service->generateSuggestions($data);
            
            return response()->json([
                'success' => true,
                'data' => $suggestions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 导出活动设计
     */
    public function export(int $id): JsonResponse
    {
        try {
            $result = $this->service->exportDesign($id);
            
            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 导入活动设计
     */
    public function import(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'data' => 'required|array',
                'data.title' => 'required|string|max:255',
                'data.subject' => 'required|string|max:100',
                'data.grade' => 'required|string|max:50',
                'data.duration' => 'required|integer|min:1',
                'data.objectives' => 'required|array',
                'data.objectives.*' => 'string'
            ]);

            $design = $this->service->importDesign($data['data'], $request->user()->id);
            
            return response()->json([
                'success' => true,
                'data' => $design
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 