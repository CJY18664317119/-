<?php

namespace App\Repositories\Impl;

use App\Contracts\Repositories\CollaborationRepositoryInterface;
use App\Models\Collaboration;
use Illuminate\Support\Facades\DB;

class CollaborationRepositoryImpl implements CollaborationRepositoryInterface
{
    /**
     * 创建协作
     */
    public function create(array $data): array
    {
        $collaboration = Collaboration::create($data);
        return $collaboration->toArray();
    }

    /**
     * 获取用户的协作列表
     */
    public function getUserCollaborations(int $userId, string $type = 'all', string $status = 'all', int $limit = 10): array
    {
        $query = Collaboration::with(['owner', 'collaborator', 'resource'])
            ->where(function ($query) use ($userId) {
                $query->where('owner_id', $userId)
                    ->orWhere('collaborator_id', $userId);
            });

        if ($type !== 'all') {
            $query->where('type', $type);
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate($limit)
            ->toArray();
    }

    /**
     * 获取单个协作详情
     */
    public function find(int $id): array
    {
        $collaboration = Collaboration::with(['owner', 'collaborator', 'resource'])
            ->findOrFail($id);
        return $collaboration->toArray();
    }

    /**
     * 更新协作状态
     */
    public function updateStatus(int $id, string $status): array
    {
        $collaboration = Collaboration::findOrFail($id);
        $collaboration->status = $status;
        $collaboration->save();
        return $collaboration->toArray();
    }

    /**
     * 删除协作
     */
    public function delete(int $id): bool
    {
        return Collaboration::destroy($id);
    }

    /**
     * 获取资源的协作列表
     */
    public function getResourceCollaborations(string $resourceType, int $resourceId): array
    {
        return Collaboration::with(['owner', 'collaborator'])
            ->where('resource_type', $resourceType)
            ->where('resource_id', $resourceId)
            ->where('status', 'accepted')
            ->get()
            ->toArray();
    }

    /**
     * 检查用户对资源的权限
     */
    public function checkUserPermission(int $userId, string $resourceType, int $resourceId, string $permission): bool
    {
        return Collaboration::where('resource_type', $resourceType)
            ->where('resource_id', $resourceId)
            ->where('collaborator_id', $userId)
            ->where('status', 'accepted')
            ->where('permissions', 'like', "%{$permission}%")
            ->exists();
    }

    /**
     * 获取用户的所有协作资源
     */
    public function getUserCollaboratedResources(int $userId, string $resourceType, int $limit = 10): array
    {
        return Collaboration::with('resource')
            ->where('collaborator_id', $userId)
            ->where('resource_type', $resourceType)
            ->where('status', 'accepted')
            ->orderBy('created_at', 'desc')
            ->paginate($limit)
            ->toArray();
    }

    /**
     * 搜索协作
     */
    public function search(int $userId, string $keyword, int $limit = 10): array
    {
        return Collaboration::with(['owner', 'collaborator', 'resource'])
            ->where(function ($query) use ($userId) {
                $query->where('owner_id', $userId)
                    ->orWhere('collaborator_id', $userId);
            })
            ->where(function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($limit)
            ->toArray();
    }

    /**
     * 获取协作统计信息
     */
    public function getStatistics(int $userId): array
    {
        $stats = DB::table('collaborations')
            ->select(
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN status = "accepted" THEN 1 ELSE 0 END) as accepted'),
                DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected')
            )
            ->where('owner_id', $userId)
            ->orWhere('collaborator_id', $userId)
            ->first();

        return [
            'total' => $stats->total,
            'pending' => $stats->pending,
            'accepted' => $stats->accepted,
            'rejected' => $stats->rejected
        ];
    }
} 