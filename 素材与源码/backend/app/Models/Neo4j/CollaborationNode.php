<?php

namespace App\Models\Neo4j;

use App\Models\Neo4j\BaseNeo4jModel;

class CollaborationNode extends BaseNeo4jModel
{
    /**
     * 标签名称
     *
     * @var array
     */
    protected $label = 'Collaboration';

    /**
     * 可填充字段
     *
     * @var array
     */
    protected $fillable = [
        'collaboration_id',
        'permission',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * 获取协作资源
     *
     * @return \Vinelab\NeoEloquent\Eloquent\Relations\BelongsTo
     */
    public function resource()
    {
        return $this->belongsTo(ResourceNode::class, 'HAS_COLLABORATION');
    }

    /**
     * 获取协作者
     *
     * @return \Vinelab\NeoEloquent\Eloquent\Relations\BelongsTo
     */
    public function collaborator()
    {
        return $this->belongsTo(UserNode::class, 'COLLABORATES_WITH');
    }
} 