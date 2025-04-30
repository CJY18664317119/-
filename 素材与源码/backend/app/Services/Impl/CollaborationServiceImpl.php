<?php

namespace App\Services\Impl;

use App\Contracts\Repositories\CollaborationRepositoryInterface;
use App\Contracts\Repositories\CollaborationHistoryRepositoryInterface;
use App\Contracts\Services\CollaborationServiceInterface;
use App\Events\CollaborationCreated;
use App\Events\CollaborationStatusChanged;
use App\Events\CollaborationPermissionUpdated;
use App\Models\CollaborationHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CollaborationServiceImpl implements CollaborationServiceInterface
{
    protected $repository;
    protected $historyRepository;

    public function __construct(
        CollaborationRepositoryInterface $repository,
        CollaborationHistoryRepositoryInterface $historyRepository
    ) {
        $this->repository = $repository;
        $this->historyRepository = $historyRepository;
    }

    /**
     * 创建协作邀请
     */
    public function createInvitation(array $data): array
    {
        try {
            DB::beginTransaction();
            
            $collaboration = $this->repository->create($data);
            
            // 记录历史
            $this->logHistory($collaboration['id'], CollaborationHistory::ACTION_CREATED, [
                'details' => ['message' => '创建协作邀请'],
                'changes' => $data
            ]);
            
            // 触发协作创建事件
            event(new CollaborationCreated($collaboration));
            
            DB::commit();
            return $collaboration;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * 获取用户的协作列表
     */
    public function getUserCollaborations(int $userId, string $type = 'all', string $status = 'all', int $limit = 10): array
    {
        return $this->repository->getUserCollaborations($userId, $type, $status, $limit);
    }

    /**
     * 获取协作详情
     */
    public function getCollaboration(int $id): array
    {
        return $this->repository->find($id);
    }

    /**
     * 更新协作状态
     */
    public function updateStatus(int $id, string $status): array
    {
        try {
            DB::beginTransaction();
            
            $oldCollaboration = $this->repository->find($id);
            $collaboration = $this->repository->updateStatus($id, $status);
            
            // 记录历史
            $this->logHistory($id, CollaborationHistory::ACTION_STATUS_CHANGED, [
                'details' => ['message' => '更新协作状态'],
                'changes' => [
                    'old_status' => $oldCollaboration['status'],
                    'new_status' => $status
                ]
            ]);
            
            // 触发状态变更事件
            event(new CollaborationStatusChanged($collaboration));
            
            DB::commit();
            return $collaboration;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * 删除协作
     */
    public function deleteCollaboration(int $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * 获取资源的协作列表
     */
    public function getResourceCollaborations(string $resourceType, int $resourceId): array
    {
        return $this->repository->getResourceCollaborations($resourceType, $resourceId);
    }

    /**
     * 检查用户权限
     */
    public function checkUserPermission(int $userId, string $resourceType, int $resourceId, string $permission): bool
    {
        return $this->repository->checkUserPermission($userId, $resourceType, $resourceId, $permission);
    }

    /**
     * 获取用户的协作资源
     */
    public function getUserCollaboratedResources(int $userId, string $resourceType, int $limit = 10): array
    {
        return $this->repository->getUserCollaboratedResources($userId, $resourceType, $limit);
    }

    /**
     * 搜索协作
     */
    public function searchCollaborations(int $userId, string $keyword, int $limit = 10): array
    {
        return $this->repository->search($userId, $keyword, $limit);
    }

    /**
     * 获取协作统计信息
     */
    public function getCollaborationStatistics(int $userId): array
    {
        return $this->repository->getStatistics($userId);
    }

    /**
     * 批量处理协作邀请
     */
    public function batchProcessInvitations(array $ids, string $status): array
    {
        $results = [];
        foreach ($ids as $id) {
            try {
                $results[$id] = $this->updateStatus($id, $status);
            } catch (\Exception $e) {
                $results[$id] = ['error' => $e->getMessage()];
            }
        }
        return $results;
    }

    /**
     * 更新协作权限
     */
    public function updatePermission(int $id, string $permission): array
    {
        try {
            DB::beginTransaction();
            
            $oldCollaboration = $this->repository->find($id);
            $collaboration = $oldCollaboration;
            $collaboration['permission'] = $permission;
            $updated = $this->repository->update($id, $collaboration);
            
            // 记录历史
            $this->logHistory($id, CollaborationHistory::ACTION_PERMISSION_CHANGED, [
                'details' => ['message' => '更新协作权限'],
                'changes' => [
                    'old_permission' => $oldCollaboration['permission'],
                    'new_permission' => $permission
                ]
            ]);
            
            // 触发权限更新事件
            event(new CollaborationPermissionUpdated($updated));
            
            DB::commit();
            return $updated;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * 获取协作历史记录
     */
    public function getCollaborationHistory(int $id): array
    {
        return CollaborationHistory::with(['user'])
            ->where('collaboration_id', $id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($history) {
                return [
                    'id' => $history->id,
                    'action' => $history->action,
                    'action_name' => $history->action_name,
                    'details' => $history->details,
                    'changes' => $history->changes,
                    'user' => [
                        'id' => $history->user->id,
                        'name' => $history->user->name,
                        'email' => $history->user->email
                    ],
                    'created_at' => $history->created_at->toDateTimeString()
                ];
            })
            ->toArray();
    }

    /**
     * 获取用户的历史记录
     */
    public function getUserHistory(int $userId, int $limit = 10): array
    {
        return $this->historyRepository->getUserHistory($userId, $limit);
    }

    /**
     * 搜索历史记录
     */
    public function searchHistory(int $collaborationId, string $keyword, int $limit = 10): array
    {
        return $this->historyRepository->search($collaborationId, $keyword, $limit);
    }

    /**
     * 按动作类型获取历史记录
     */
    public function getHistoryByAction(int $collaborationId, string $action, int $limit = 10): array
    {
        return $this->historyRepository->getByAction($collaborationId, $action, $limit);
    }

    /**
     * 获取历史记录统计
     */
    public function getHistoryStatistics(int $collaborationId): array
    {
        return $this->historyRepository->getStatistics($collaborationId);
    }

    /**
     * 导出历史记录
     */
    public function exportHistory(int $collaborationId): array
    {
        return $this->historyRepository->export($collaborationId);
    }

    /**
     * 清理历史记录
     */
    public function cleanupHistory(int $days = 30): int
    {
        return $this->historyRepository->cleanup($days);
    }

    /**
     * 记录协作历史
     */
    protected function logHistory(int $collaborationId, string $action, array $data = []): void
    {
        $this->historyRepository->create([
            'collaboration_id' => $collaborationId,
            'user_id' => Auth::id(),
            'action' => $action,
            'details' => $data['details'] ?? null,
            'changes' => $data['changes'] ?? null
        ]);
    }
} 