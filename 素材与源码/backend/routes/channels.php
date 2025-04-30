<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Broadcast::channel('collaboration.{id}', function ($user, $id) {
    return true; // Authorization is handled in the WebSocketController
}); 