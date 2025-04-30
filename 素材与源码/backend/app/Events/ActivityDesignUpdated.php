<?php

namespace App\Events;

use App\Models\ActivityDesign;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActivityDesignUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $design;
    public $changes;

    public function __construct(ActivityDesign $design, array $changes)
    {
        $this->design = $design;
        $this->changes = $changes;
    }
} 