<?php

namespace App\Contracts\Services;

interface ActivityDesignServiceInterface
{
    /**
     * 创建活动设计
     */
    public function createDesign(array $data): array;

    /**
     * 获取活动设计详情
     */
    public function getDesign(int $id): array;

    /**
     * 获取用户的活动设计列表
     */
    public function getUserDesigns(int $userId, int $limit = 10): array;

    /**
     * 更新活动设计
     */
    public function updateDesign(int $id, array $data): array;

    /**
     * 删除活动设计
     */
    public function deleteDesign(int $id): bool;

    /**
     * 搜索活动设计
     */
    public function searchDesigns(int $userId, string $keyword, int $limit = 10): array;

    /**
     * 获取活动设计模板
     */
    public function getTemplates(int $limit = 10): array;

    /**
     * 复制活动设计
     */
    public function duplicateDesign(int $id, int $userId): array;

    /**
     * 更新活动设计状态
     */
    public function updateDesignStatus(int $id, string $status): array;

    /**
     * 生成活动设计建议
     */
    public function generateSuggestions(array $data): array;

    /**
     * 导出活动设计
     */
    public function exportDesign(int $id): array;

    /**
     * 导入活动设计
     */
    public function importDesign(array $data, int $userId): array;
} 