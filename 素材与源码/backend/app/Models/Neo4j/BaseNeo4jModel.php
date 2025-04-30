<?php

namespace App\Models\Neo4j;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

abstract class BaseNeo4jModel extends NeoEloquent
{
    /**
     * 默认连接名称
     *
     * @var string
     */
    protected $connection = 'neo4j';

    /**
     * 默认时间格式
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * 默认时间戳
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * 创建时间字段
     *
     * @var string
     */
    const CREATED_AT = 'created_at';

    /**
     * 更新时间字段
     *
     * @var string
     */
    const UPDATED_AT = 'updated_at';

    /**
     * 获取模型的主键
     *
     * @return string
     */
    public function getKeyName()
    {
        return 'id';
    }

    /**
     * 获取模型的主键类型
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'int';
    }

    /**
     * 获取模型的自动递增键
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return true;
    }
} 