<?php

namespace App\Listeners;

use App\Events\ActivityDesignCreated;
use App\Events\ActivityDesignUpdated;
use App\Events\ActivityDesignStatusChanged;
use App\Notifications\ActivityDesignNotification;
use Illuminate\Support\Facades\Notification;

class NotifyActivityDesignChanges
{
    public function handleCreated(ActivityDesignCreated $event)
    {
        $user = $event->design->user;
        Notification::send($user, new ActivityDesignNotification(
            '活动设计已创建',
            "您已成功创建活动设计：{$event->design->title}"
        ));
    }

    public function handleUpdated(ActivityDesignUpdated $event)
    {
        $user = $event->design->user;
        $changes = implode(', ', array_keys($event->changes));
        
        Notification::send($user, new ActivityDesignNotification(
            '活动设计已更新',
            "您的活动设计 {$event->design->title} 已更新，修改了：{$changes}"
        ));
    }

    public function handleStatusChanged(ActivityDesignStatusChanged $event)
    {
        $user = $event->design->user;
        $statusMap = [
            'draft' => '草稿',
            'published' => '已发布',
            'archived' => '已归档'
        ];
        
        $oldStatus = $statusMap[$event->oldStatus] ?? $event->oldStatus;
        $newStatus = $statusMap[$event->newStatus] ?? $event->newStatus;
        
        Notification::send($user, new ActivityDesignNotification(
            '活动设计状态已变更',
            "您的活动设计 {$event->design->title} 状态已从 {$oldStatus} 变更为 {$newStatus}"
        ));
    }
} 