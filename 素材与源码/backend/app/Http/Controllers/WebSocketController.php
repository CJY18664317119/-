<?php

namespace App\Http\Controllers;

use App\Contracts\Services\CollaborationServiceInterface;
use App\Events\CollaborationNodeUpdated;
use App\Events\CollaboratorJoined;
use App\Events\CollaboratorLeft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebSocketController extends Controller
{
    protected $collaborationService;

    public function __construct(CollaborationServiceInterface $collaborationService)
    {
        $this->collaborationService = $collaborationService;
    }

    public function handleConnection(Request $request, $collaborationId)
    {
        // Verify user has access to this collaboration
        if (!$this->collaborationService->checkUserPermission(
            Auth::id(),
            'mindmap',
            $collaborationId,
            'view'
        )) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Broadcast collaborator joined event
        event(new CollaboratorJoined([
            'collaborationId' => $collaborationId,
            'userId' => Auth::id(),
            'userName' => Auth::user()->name,
            'color' => $this->getUserColor(Auth::id())
        ]));

        return response()->json(['status' => 'connected']);
    }

    public function handleNodeUpdate(Request $request, $collaborationId)
    {
        $data = $request->validate([
            'nodeId' => 'required|integer',
            'x' => 'required|numeric',
            'y' => 'required|numeric'
        ]);

        // Verify user has permission to edit
        if (!$this->collaborationService->checkUserPermission(
            Auth::id(),
            'mindmap',
            $collaborationId,
            'edit'
        )) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Broadcast node update event
        event(new CollaborationNodeUpdated([
            'collaborationId' => $collaborationId,
            'nodeId' => $data['nodeId'],
            'x' => $data['x'],
            'y' => $data['y'],
            'userId' => Auth::id()
        ]));

        return response()->json(['status' => 'updated']);
    }

    public function handleDisconnect(Request $request, $collaborationId)
    {
        // Broadcast collaborator left event
        event(new CollaboratorLeft([
            'collaborationId' => $collaborationId,
            'userId' => Auth::id()
        ]));

        return response()->json(['status' => 'disconnected']);
    }

    protected function getUserColor($userId)
    {
        // Generate a consistent color for each user
        $colors = [
            '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4',
            '#FFEEAD', '#D4A5A5', '#9B59B6', '#3498DB',
            '#E74C3C', '#2ECC71'
        ];
        return $colors[$userId % count($colors)];
    }
} 