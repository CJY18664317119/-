<?php

namespace App\Events;

use App\Models\ActivityDesign;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActivityDesignStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $design;
    public $oldStatus;
    public $newStatus;

    public function __construct(ActivityDesign $design, string $oldStatus, string $newStatus)
    {
        $this->design = $design;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }
} 