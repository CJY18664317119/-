<?php

namespace App\Models\Neo4j;

use App\Models\Neo4j\BaseNeo4jModel;

class UserNode extends BaseNeo4jModel
{
    /**
     * 标签名称
     *
     * @var array
     */
    protected $label = 'User';

    /**
     * 可填充字段
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'role',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * 隐藏字段
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * 获取用户创建的资源
     *
     * @return \Vinelab\NeoEloquent\Eloquent\Relations\HasMany
     */
    public function createdResources()
    {
        return $this->hasMany(ResourceNode::class, 'CREATED_BY');
    }

    /**
     * 获取用户协作的资源
     *
     * @return \Vinelab\NeoEloquent\Eloquent\Relations\HasMany
     */
    public function collaboratedResources()
    {
        return $this->hasMany(ResourceNode::class, 'COLLABORATES_WITH');
    }

    /**
     * 获取用户的协作关系
     *
     * @return \Vinelab\NeoEloquent\Eloquent\Relations\HasMany
     */
    public function collaborations()
    {
        return $this->hasMany(CollaborationNode::class, 'HAS_COLLABORATION');
    }
} 