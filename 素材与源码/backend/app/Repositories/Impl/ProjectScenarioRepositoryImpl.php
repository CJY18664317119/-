<?php

namespace App\Repositories\Impl;

use App\Contracts\Repositories\ProjectScenarioRepositoryInterface;
use App\Models\ProjectScenario;
use Illuminate\Database\Eloquent\Collection;

class ProjectScenarioRepositoryImpl implements ProjectScenarioRepositoryInterface
{
    protected $model;

    public function __construct(ProjectScenario $model)
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

    public function getUserScenarios(int $userId, int $limit = 10)
    {
        return $this->model->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function update(int $id, array $data)
    {
        $scenario = $this->find($id);
        $scenario->update($data);
        return $scenario;
    }

    public function delete(int $id)
    {
        $scenario = $this->find($id);
        return $scenario->delete();
    }

    public function search(int $userId, string $keyword, int $limit = 10)
    {
        return $this->model->where('user_id', $userId)
            ->where(function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('subject', 'like', "%{$keyword}%")
                    ->orWhere('grade', 'like', "%{$keyword}%")
                    ->orWhere('background', 'like', "%{$keyword}%");
            })
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getTemplates(int $limit = 10)
    {
        return $this->model->where('is_template', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function duplicate(int $id, int $userId)
    {
        $scenario = $this->find($id);
        
        $newScenario = $scenario->replicate();
        $newScenario->user_id = $userId;
        $newScenario->title = $scenario->title . ' (副本)';
        $newScenario->is_template = false;
        $newScenario->status = 'draft';
        $newScenario->save();
        
        return $newScenario;
    }

    public function updateStatus(int $id, string $status)
    {
        $scenario = $this->find($id);
        $scenario->status = $status;
        $scenario->save();
        return $scenario;
    }

    public function generateSuggestions(array $data)
    {
        // 这里可以调用 AI 服务生成建议
        // 暂时返回空数组
        return [];
    }

    public function export(int $id)
    {
        $scenario = $this->find($id);
        return $scenario->toArray();
    }

    public function import(array $data, int $userId)
    {
        $data['user_id'] = $userId;
        $data['is_template'] = false;
        return $this->create($data);
    }
} 