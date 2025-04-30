<?php

namespace App\Contracts\Repositories;

interface ProjectScenarioRepositoryInterface
{
    /**
     * 创建项目情境
     */
    public function create(array $data);

    /**
     * 获取单个项目情境
     */
    public function find(int $id);

    /**
     * 获取用户的项目情境列表
     */
    public function getUserScenarios(int $userId, int $limit = 10);

    /**
     * 更新项目情境
     */
    public function update(int $id, array $data);

    /**
     * 删除项目情境
     */
    public function delete(int $id);

    /**
     * 搜索项目情境
     */
    public function search(int $userId, string $keyword, int $limit = 10);

    /**
     * 获取项目情境模板
     */
    public function getTemplates(int $limit = 10);

    /**
     * 复制项目情境
     */
    public function duplicate(int $id, int $userId);

    /**
     * 更新项目情境状态
     */
    public function updateStatus(int $id, string $status);

    /**
     * 生成项目情境建议
     */
    public function generateSuggestions(array $data);

    /**
     * 导出项目情境
     */
    public function export(int $id);

    /**
     * 导入项目情境
     */
    public function import(array $data, int $userId);
} 