<?php

namespace App\Repositories\Impl;

use App\Contracts\Repositories\ActivityDesignRepositoryInterface;
use App\Models\ActivityDesign;
use Illuminate\Database\Eloquent\Collection;

class ActivityDesignRepositoryImpl implements ActivityDesignRepositoryInterface
{
    protected $model;

    public function __construct(ActivityDesign $model)
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

    public function getUserDesigns(int $userId, int $limit = 10)
    {
        return $this->model->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function update(int $id, array $data)
    {
        $design = $this->find($id);
        $design->update($data);
        return $design;
    }

    public function delete(int $id)
    {
        $design = $this->find($id);
        return $design->delete();
    }

    public function search(int $userId, string $keyword, int $limit = 10)
    {
        return $this->model->where('user_id', $userId)
            ->where(function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('subject', 'like', "%{$keyword}%")
                    ->orWhere('grade', 'like', "%{$keyword}%");
            })
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getTemplates(int $limit = 10)
    {
        return $this->model->where('status', 'template')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function duplicate(int $id, int $userId)
    {
        $design = $this->find($id);
        
        $newDesign = $design->replicate();
        $newDesign->user_id = $userId;
        $newDesign->title = $design->title . ' (副本)';
        $newDesign->status = 'draft';
        $newDesign->save();
        
        return $newDesign;
    }

    public function updateStatus(int $id, string $status)
    {
        $design = $this->find($id);
        $design->status = $status;
        $design->save();
        return $design;
    }
} 