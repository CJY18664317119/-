<?php

namespace App\Models\Neo4j;

use App\Models\Neo4j\BaseNeo4jModel;

class ResourceNode extends BaseNeo4jModel
{
    /**
     * 标签名称
     *
     * @var array
     */
    protected $label = 'Resource';

    /**
     * 可填充字段
     *
     * @var array
     */
    protected $fillable = [
        'resource_id',
        'title',
        'description',
        'type',
        'content',
        'status',
        'visibility',
        'created_at',
        'updated_at'
    ];

    /**
     * 获取资源创建者
     *
     * @return \Vinelab\NeoEloquent\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(UserNode::class, 'CREATED_BY');
    }

    /**
     * 获取资源的协作关系
     *
     * @return \Vinelab\NeoEloquent\Eloquent\Relations\HasMany
     */
    public function collaborations()
    {
        return $this->hasMany(CollaborationNode::class, 'HAS_COLLABORATION');
    }

    /**
     * 获取资源的协作者
     *
     * @return \Vinelab\NeoEloquent\Eloquent\Relations\HasMany
     */
    public function collaborators()
    {
        return $this->hasMany(UserNode::class, 'COLLABORATES_WITH');
    }
} 