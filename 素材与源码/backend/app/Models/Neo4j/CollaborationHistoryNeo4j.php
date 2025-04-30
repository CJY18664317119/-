<?php

namespace App\Models\Neo4j;

use Vinelab\NeoEloquent\Eloquent\Model;

class CollaborationHistoryNeo4j extends Model
{
    protected $connection = 'neo4j';
    protected $label = 'CollaborationHistory';
    
    protected $fillable = [
        'collaboration_id',
        'user_id',
        'action',
        'action_name',
        'timestamp',
        'relationships'
    ];

    protected $casts = [
        'relationships' => 'array',
        'timestamp' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Neo4j\UserNeo4j', 'HAS_USER');
    }

    public function collaboration()
    {
        return $this->belongsTo('App\Models\Neo4j\CollaborationNeo4j', 'BELONGS_TO');
    }

    public function relatedResources()
    {
        return $this->hasMany('App\Models\Neo4j\ResourceNeo4j', 'RELATED_TO');
    }

    public function relatedUsers()
    {
        return $this->hasMany('App\Models\Neo4j\UserNeo4j', 'INVOLVED_IN');
    }
} 