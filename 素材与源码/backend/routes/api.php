<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AIController;
use App\Http\Controllers\ActivityDesignController;
use App\Http\Controllers\ProjectScenarioController;
use App\Http\Controllers\CollaborationController;
use App\Http\Controllers\WebSocketController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// AI 相关路由
Route::prefix('ai')->group(function () {
    Route::post('/chat', [AIController::class, 'chat']);
    Route::get('/chat-history', [AIController::class, 'getChatHistory']);
    Route::get('/chat/{id}', [AIController::class, 'getChat']);
    Route::delete('/chat/{id}', [AIController::class, 'deleteChat']);
    Route::post('/save-chat', [AIController::class, 'saveChat']);
    Route::get('/export-chat/{id}', [AIController::class, 'exportChat']);
    
    Route::post('/generate-lesson-plan', [AIController::class, 'generateLessonPlan']);
    Route::post('/generate-courseware', [AIController::class, 'generateCourseware']);
    Route::post('/analyze-article', [AIController::class, 'analyzeArticle']);
    Route::post('/correct-essay', [AIController::class, 'correctEssay']);
});

// 活动设计相关路由
Route::prefix('activity-designs')->group(function () {
    Route::post('/', [ActivityDesignController::class, 'create']);
    Route::get('/{id}', [ActivityDesignController::class, 'get']);
    Route::get('/user/list', [ActivityDesignController::class, 'getUserDesigns']);
    Route::put('/{id}', [ActivityDesignController::class, 'update']);
    Route::delete('/{id}', [ActivityDesignController::class, 'delete']);
    Route::get('/search', [ActivityDesignController::class, 'search']);
    Route::get('/templates', [ActivityDesignController::class, 'getTemplates']);
    Route::post('/{id}/duplicate', [ActivityDesignController::class, 'duplicate']);
    Route::put('/{id}/status', [ActivityDesignController::class, 'updateStatus']);
    Route::post('/generate-suggestions', [ActivityDesignController::class, 'generateSuggestions']);
    Route::get('/{id}/export', [ActivityDesignController::class, 'export']);
    Route::post('/import', [ActivityDesignController::class, 'import']);
});

// 项目情境相关路由
Route::prefix('project-scenarios')->group(function () {
    // 基本 CRUD 操作
    Route::post('/', [ProjectScenarioController::class, 'create']);
    Route::get('/{id}', [ProjectScenarioController::class, 'get']);
    Route::get('/user/list', [ProjectScenarioController::class, 'getUserScenarios']);
    Route::put('/{id}', [ProjectScenarioController::class, 'update']);
    Route::delete('/{id}', [ProjectScenarioController::class, 'delete']);
    
    // 搜索和模板
    Route::get('/search', [ProjectScenarioController::class, 'search']);
    Route::get('/templates', [ProjectScenarioController::class, 'getTemplates']);
    Route::post('/{id}/duplicate', [ProjectScenarioController::class, 'duplicate']);
    
    // 状态管理
    Route::put('/{id}/status', [ProjectScenarioController::class, 'updateStatus']);
    
    // AI 辅助功能
    Route::post('/generate-suggestions', [ProjectScenarioController::class, 'generateSuggestions']);
    Route::post('/generate-background', [ProjectScenarioController::class, 'generateBackground']);
    Route::post('/generate-tasks', [ProjectScenarioController::class, 'generateTasks']);
    Route::post('/generate-resources', [ProjectScenarioController::class, 'generateResources']);
    Route::post('/generate-assessment', [ProjectScenarioController::class, 'generateAssessment']);
    
    // 导入导出
    Route::get('/{id}/export', [ProjectScenarioController::class, 'export']);
    Route::post('/import', [ProjectScenarioController::class, 'import']);
});

// 协作相关路由
Route::prefix('collaborations')->middleware(['auth:sanctum'])->group(function () {
    // 创建协作邀请
    Route::post('/invite', [CollaborationController::class, 'createInvitation']);
    
    // 获取协作列表
    Route::get('/list', [CollaborationController::class, 'getUserCollaborations']);
    
    // 获取协作详情
    Route::get('/{id}', [CollaborationController::class, 'getCollaboration']);
    
    // 更新协作状态
    Route::put('/{id}/status', [CollaborationController::class, 'updateStatus']);
    
    // 删除协作
    Route::delete('/{id}', [CollaborationController::class, 'deleteCollaboration']);
    
    // 获取资源协作列表
    Route::get('/resource/list', [CollaborationController::class, 'getResourceCollaborations']);
    
    // 检查用户权限
    Route::get('/check-permission', [CollaborationController::class, 'checkUserPermission']);
    
    // 获取用户协作资源
    Route::get('/user/resources', [CollaborationController::class, 'getUserCollaboratedResources']);
    
    // 搜索协作
    Route::get('/search', [CollaborationController::class, 'searchCollaborations']);
    
    // 获取协作统计
    Route::get('/statistics', [CollaborationController::class, 'getCollaborationStatistics']);
    
    // 批量处理邀请
    Route::post('/batch-process', [CollaborationController::class, 'batchProcessInvitations']);
    
    // 更新协作权限
    Route::put('/{id}/permission', [CollaborationController::class, 'updatePermission']);
    
    // 获取协作历史
    Route::get('/{id}/history', [CollaborationController::class, 'getCollaborationHistory']);

    // 历史记录相关路由
    Route::get('/user/history', [CollaborationController::class, 'getUserHistory']);
    Route::get('/{id}/history/search', [CollaborationController::class, 'searchHistory']);
    Route::get('/{id}/history/action', [CollaborationController::class, 'getHistoryByAction']);
    Route::get('/{id}/history/statistics', [CollaborationController::class, 'getHistoryStatistics']);
    Route::get('/{id}/history/export', [CollaborationController::class, 'exportHistory']);
    Route::delete('/history/cleanup', [CollaborationController::class, 'cleanupHistory']);

    // WebSocket routes
    Route::post('/ws/connect/{collaborationId}', [WebSocketController::class, 'handleConnection']);
    Route::post('/ws/node-update/{collaborationId}', [WebSocketController::class, 'handleNodeUpdate']);
    Route::post('/ws/disconnect/{collaborationId}', [WebSocketController::class, 'handleDisconnect']);
}); 