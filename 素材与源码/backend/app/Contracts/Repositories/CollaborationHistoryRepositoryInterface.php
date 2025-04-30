<?php

namespace App\Contracts\Repositories;

interface CollaborationHistoryRepositoryInterface
{
    /**
     * 创建历史记录
     */
    public function create(array $data): array;

    /**
     * 获取协作的历史记录
     */
    public function getCollaborationHistory(int $collaborationId, int $limit = 10): array;

    /**
     * 获取用户的历史记录
     */
    public function getUserHistory(int $userId, int $limit = 10): array;

    /**
     * 搜索历史记录
     */
    public function search(int $collaborationId, string $keyword, int $limit = 10): array;

    /**
     * 按动作类型获取历史记录
     */
    public function getByAction(int $collaborationId, string $action, int $limit = 10): array;

    /**
     * 获取历史记录统计
     */
    public function getStatistics(int $collaborationId): array;

    /**
     * 导出历史记录
     */
    public function export(int $collaborationId): array;

    /**
     * 清理历史记录
     */
    public function cleanup(int $days = 30): int;
} 