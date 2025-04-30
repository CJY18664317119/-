<?php

namespace App\Contracts\Repositories;

interface ActivityDesignRepositoryInterface
{
    /**
     * 创建活动设计
     */
    public function create(array $data);

    /**
     * 获取单个活动设计
     */
    public function find(int $id);

    /**
     * 获取用户的活动设计列表
     */
    public function getUserDesigns(int $userId, int $limit = 10);

    /**
     * 更新活动设计
     */
    public function update(int $id, array $data);

    /**
     * 删除活动设计
     */
    public function delete(int $id);

    /**
     * 搜索活动设计
     */
    public function search(int $userId, string $keyword, int $limit = 10);

    /**
     * 获取活动设计的模板
     */
    public function getTemplates(int $limit = 10);

    /**
     * 复制活动设计
     */
    public function duplicate(int $id, int $userId);

    /**
     * 更新活动设计状态
     */
    public function updateStatus(int $id, string $status);
} 