<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollaborationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'collaboration_id',
        'user_id',
        'action',
        'details',
        'changes'
    ];

    protected $casts = [
        'details' => 'array',
        'changes' => 'array'
    ];

    // 动作类型常量
    const ACTION_CREATED = 'created';
    const ACTION_UPDATED = 'updated';
    const ACTION_DELETED = 'deleted';
    const ACTION_STATUS_CHANGED = 'status_changed';
    const ACTION_PERMISSION_CHANGED = 'permission_changed';
    const ACTION_ACCEPTED = 'accepted';
    const ACTION_REJECTED = 'rejected';

    /**
     * 获取关联的协作
     */
    public function collaboration()
    {
        return $this->belongsTo(Collaboration::class);
    }

    /**
     * 获取执行操作的用户
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 获取动作的显示名称
     */
    public function getActionNameAttribute(): string
    {
        return match($this->action) {
            self::ACTION_CREATED => '创建协作',
            self::ACTION_UPDATED => '更新协作',
            self::ACTION_DELETED => '删除协作',
            self::ACTION_STATUS_CHANGED => '状态变更',
            self::ACTION_PERMISSION_CHANGED => '权限变更',
            self::ACTION_ACCEPTED => '接受邀请',
            self::ACTION_REJECTED => '拒绝邀请',
            default => '未知操作'
        };
    }
} 