<?php

namespace App\Contracts\Repositories;

interface AIChatRepositoryInterface
{
    /**
     * 创建聊天记录
     */
    public function create(array $data);

    /**
     * 获取单个聊天记录
     */
    public function find(int $id);

    /**
     * 获取用户的聊天记录列表
     */
    public function getUserChats(int $userId, int $limit = 10);

    /**
     * 更新聊天记录
     */
    public function update(int $id, array $data);

    /**
     * 删除聊天记录
     */
    public function delete(int $id);

    /**
     * 获取用户的最近聊天记录
     */
    public function getLatestChat(int $userId);

    /**
     * 搜索聊天记录
     */
    public function search(int $userId, string $keyword, int $limit = 10);
} 