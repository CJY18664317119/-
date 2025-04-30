<?php

namespace App\Events;

use App\Models\ActivityDesign;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActivityDesignCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $design;

    public function __construct(ActivityDesign $design)
    {
        $this->design = $design;
    }
} 