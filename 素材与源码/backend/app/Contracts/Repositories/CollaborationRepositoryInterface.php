<?php

namespace App\Contracts\Repositories;

interface CollaborationRepositoryInterface
{
    /**
     * 创建协作
     */
    public function create(array $data): array;

    /**
     * 获取用户的协作列表
     */
    public function getUserCollaborations(int $userId, string $type = 'all', string $status = 'all', int $limit = 10): array;

    /**
     * 获取单个协作详情
     */
    public function find(int $id): array;

    /**
     * 更新协作状态
     */
    public function updateStatus(int $id, string $status): array;

    /**
     * 删除协作
     */
    public function delete(int $id): bool;

    /**
     * 获取资源的协作列表
     */
    public function getResourceCollaborations(string $resourceType, int $resourceId): array;

    /**
     * 检查用户对资源的权限
     */
    public function checkUserPermission(int $userId, string $resourceType, int $resourceId, string $permission): bool;

    /**
     * 获取用户的所有协作资源
     */
    public function getUserCollaboratedResources(int $userId, string $resourceType, int $limit = 10): array;

    /**
     * 搜索协作
     */
    public function search(int $userId, string $keyword, int $limit = 10): array;

    /**
     * 获取协作统计信息
     */
    public function getStatistics(int $userId): array;
} 