<?php

namespace App\Repositories\Impl;

use App\Contracts\Repositories\AIChatRepositoryInterface;
use App\Models\AIChat;
use Illuminate\Database\Eloquent\Collection;

class AIChatRepositoryImpl implements AIChatRepositoryInterface
{
    protected $model;

    public function __construct(AIChat $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function getUserChats(int $userId, int $limit = 10)
    {
        return $this->model->where('user_id', $userId)
            ->orderBy('last_message_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function update(int $id, array $data)
    {
        $chat = $this->find($id);
        $chat->update($data);
        return $chat;
    }

    public function delete(int $id)
    {
        $chat = $this->find($id);
        return $chat->delete();
    }

    public function getLatestChat(int $userId)
    {
        return $this->model->where('user_id', $userId)
            ->orderBy('last_message_at', 'desc')
            ->first();
    }

    public function search(int $userId, string $keyword, int $limit = 10)
    {
        return $this->model->where('user_id', $userId)
            ->where(function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('messages', 'like', "%{$keyword}%");
            })
            ->orderBy('last_message_at', 'desc')
            ->limit($limit)
            ->get();
    }
} 