<?php

namespace App\Listeners;

use App\Events\ActivityDesignCreated;
use App\Events\ActivityDesignUpdated;
use App\Events\ActivityDesignStatusChanged;
use Illuminate\Support\Facades\Log;

class LogActivityDesignEvent
{
    public function handleCreated(ActivityDesignCreated $event)
    {
        Log::info('Activity Design Created', [
            'id' => $event->design->id,
            'title' => $event->design->title,
            'user_id' => $event->design->user_id
        ]);
    }

    public function handleUpdated(ActivityDesignUpdated $event)
    {
        Log::info('Activity Design Updated', [
            'id' => $event->design->id,
            'changes' => $event->changes
        ]);
    }

    public function handleStatusChanged(ActivityDesignStatusChanged $event)
    {
        Log::info('Activity Design Status Changed', [
            'id' => $event->design->id,
            'old_status' => $event->oldStatus,
            'new_status' => $event->newStatus
        ]);
    }
} 