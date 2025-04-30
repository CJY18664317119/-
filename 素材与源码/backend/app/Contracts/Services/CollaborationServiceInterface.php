<?php

namespace App\Contracts\Services;

interface CollaborationServiceInterface
{
    /**
     * 创建协作邀请
     */
    public function createInvitation(array $data): array;

    /**
     * 获取用户的协作列表
     */
    public function getUserCollaborations(int $userId, string $type = 'all', string $status = 'all', int $limit = 10): array;

    /**
     * 获取协作详情
     */
    public function getCollaboration(int $id): array;

    /**
     * 更新协作状态
     */
    public function updateStatus(int $id, string $status): array;

    /**
     * 删除协作
     */
    public function deleteCollaboration(int $id): bool;

    /**
     * 获取资源的协作列表
     */
    public function getResourceCollaborations(string $resourceType, int $resourceId): array;

    /**
     * 检查用户权限
     */
    public function checkUserPermission(int $userId, string $resourceType, int $resourceId, string $permission): bool;

    /**
     * 获取用户的协作资源
     */
    public function getUserCollaboratedResources(int $userId, string $resourceType, int $limit = 10): array;

    /**
     * 搜索协作
     */
    public function searchCollaborations(int $userId, string $keyword, int $limit = 10): array;

    /**
     * 获取协作统计信息
     */
    public function getCollaborationStatistics(int $userId): array;

    /**
     * 批量处理协作邀请
     */
    public function batchProcessInvitations(array $ids, string $status): array;

    /**
     * 更新协作权限
     */
    public function updatePermission(int $id, string $permission): array;

    /**
     * 获取协作历史记录
     */
    public function getCollaborationHistory(int $id): array;

    /**
     * 获取用户的历史记录
     */
    public function getUserHistory(int $userId, int $limit = 10): array;

    /**
     * 搜索历史记录
     */
    public function searchHistory(int $collaborationId, string $keyword, int $limit = 10): array;

    /**
     * 按动作类型获取历史记录
     */
    public function getHistoryByAction(int $collaborationId, string $action, int $limit = 10): array;

    /**
     * 获取历史记录统计
     */
    public function getHistoryStatistics(int $collaborationId): array;

    /**
     * 导出历史记录
     */
    public function exportHistory(int $collaborationId): array;

    /**
     * 清理历史记录
     */
    public function cleanupHistory(int $days = 30): int;
} 