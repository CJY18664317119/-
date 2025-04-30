<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collaboration extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_type',
        'resource_id',
        'owner_id',
        'collaborator_id',
        'permission',
        'status',
        'notes'
    ];

    protected $casts = [
        'notes' => 'array'
    ];

    // 资源类型常量
    const TYPE_ACTIVITY = 'activity';
    const TYPE_PROJECT = 'project';
    const TYPE_LESSON = 'lesson';
    const TYPE_COURSEWARE = 'courseware';

    // 权限常量
    const PERMISSION_VIEW = 'view';
    const PERMISSION_EDIT = 'edit';
    const PERMISSION_ADMIN = 'admin';

    // 状态常量
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';

    /**
     * 获取资源所有者
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * 获取协作者
     */
    public function collaborator()
    {
        return $this->belongsTo(User::class, 'collaborator_id');
    }

    /**
     * 获取关联的资源
     */
    public function resource()
    {
        switch ($this->resource_type) {
            case self::TYPE_ACTIVITY:
                return $this->belongsTo(ActivityDesign::class, 'resource_id');
            case self::TYPE_PROJECT:
                return $this->belongsTo(ProjectScenario::class, 'resource_id');
            case self::TYPE_LESSON:
                return $this->belongsTo(LessonPlan::class, 'resource_id');
            case self::TYPE_COURSEWARE:
                return $this->belongsTo(Courseware::class, 'resource_id');
            default:
                return null;
        }
    }

    /**
     * 检查用户是否有特定权限
     */
    public function hasPermission(User $user, string $permission): bool
    {
        if ($user->id === $this->owner_id) {
            return true;
        }

        if ($user->id === $this->collaborator_id) {
            return $this->permission === $permission || $this->permission === self::PERMISSION_ADMIN;
        }

        return false;
    }

    /**
     * 检查协作是否处于活动状态
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }
} 