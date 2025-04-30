<?php

namespace App\Repositories\Impl;

use App\Contracts\Repositories\CollaborationHistoryRepositoryInterface;
use App\Models\CollaborationHistory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CollaborationHistoryRepositoryImpl implements CollaborationHistoryRepositoryInterface
{
    /**
     * 创建历史记录
     */
    public function create(array $data): array
    {
        $history = CollaborationHistory::create($data);
        return $history->toArray();
    }

    /**
     * 获取协作的历史记录
     */
    public function getCollaborationHistory(int $collaborationId, int $limit = 10): array
    {
        return CollaborationHistory::with(['user'])
            ->where('collaboration_id', $collaborationId)
            ->orderBy('created_at', 'desc')
            ->paginate($limit)
            ->toArray();
    }

    /**
     * 获取用户的历史记录
     */
    public function getUserHistory(int $userId, int $limit = 10): array
    {
        return CollaborationHistory::with(['collaboration'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($limit)
            ->toArray();
    }

    /**
     * 搜索历史记录
     */
    public function search(int $collaborationId, string $keyword, int $limit = 10): array
    {
        return CollaborationHistory::with(['user'])
            ->where('collaboration_id', $collaborationId)
            ->where(function ($query) use ($keyword) {
                $query->where('action', 'like', "%{$keyword}%")
                    ->orWhere('details', 'like', "%{$keyword}%")
                    ->orWhereHas('user', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%")
                            ->orWhere('email', 'like', "%{$keyword}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($limit)
            ->toArray();
    }

    /**
     * 按动作类型获取历史记录
     */
    public function getByAction(int $collaborationId, string $action, int $limit = 10): array
    {
        return CollaborationHistory::with(['user'])
            ->where('collaboration_id', $collaborationId)
            ->where('action', $action)
            ->orderBy('created_at', 'desc')
            ->paginate($limit)
            ->toArray();
    }

    /**
     * 获取历史记录统计
     */
    public function getStatistics(int $collaborationId): array
    {
        $stats = DB::table('collaboration_histories')
            ->select(
                DB::raw('COUNT(*) as total'),
                DB::raw('COUNT(DISTINCT user_id) as unique_users'),
                DB::raw('COUNT(CASE WHEN action = "created" THEN 1 END) as created'),
                DB::raw('COUNT(CASE WHEN action = "updated" THEN 1 END) as updated'),
                DB::raw('COUNT(CASE WHEN action = "deleted" THEN 1 END) as deleted'),
                DB::raw('COUNT(CASE WHEN action = "status_changed" THEN 1 END) as status_changed'),
                DB::raw('COUNT(CASE WHEN action = "permission_changed" THEN 1 END) as permission_changed')
            )
            ->where('collaboration_id', $collaborationId)
            ->first();

        return [
            'total' => $stats->total,
            'unique_users' => $stats->unique_users,
            'created' => $stats->created,
            'updated' => $stats->updated,
            'deleted' => $stats->deleted,
            'status_changed' => $stats->status_changed,
            'permission_changed' => $stats->permission_changed
        ];
    }

    /**
     * 导出历史记录
     */
    public function export(int $collaborationId): array
    {
        return CollaborationHistory::with(['user'])
            ->where('collaboration_id', $collaborationId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($history) {
                return [
                    'id' => $history->id,
                    'action' => $history->action,
                    'action_name' => $history->action_name,
                    'details' => $history->details,
                    'changes' => $history->changes,
                    'user' => [
                        'id' => $history->user->id,
                        'name' => $history->user->name,
                        'email' => $history->user->email
                    ],
                    'created_at' => $history->created_at->toDateTimeString()
                ];
            })
            ->toArray();
    }

    /**
     * 清理历史记录
     */
    public function cleanup(int $days = 30): int
    {
        $date = Carbon::now()->subDays($days);
        return CollaborationHistory::where('created_at', '<', $date)->delete();
    }
} 