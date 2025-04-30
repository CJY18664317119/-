<?php

namespace App\Models\Mongo;

use Jenssegers\Mongodb\Eloquent\Model;

class CollaborationHistoryMongo extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'collaboration_histories';
    
    protected $fillable = [
        'collaboration_id',
        'user_id',
        'action',
        'action_name',
        'details',
        'changes',
        'metadata',
        'tags',
        'attachments'
    ];

    protected $casts = [
        'details' => 'array',
        'changes' => 'array',
        'metadata' => 'array',
        'tags' => 'array',
        'attachments' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function collaboration()
    {
        return $this->belongsTo('App\Models\Collaboration', 'collaboration_id');
    }
} 