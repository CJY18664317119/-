# 活动设计 API 文档

## 概述

活动设计 API 提供了一系列端点，用于管理教学活动的设计、创建、更新、删除、搜索等操作，以及使用 AI 生成活动设计的各个组成部分。

## 基础路径

所有 API 端点都以 `/api/activity-designs` 为基础路径。

## 认证

所有 API 端点都需要通过 Bearer Token 进行认证。请在请求头中添加：

```
Authorization: Bearer {token}
```

## 端点列表

### 基本 CRUD 操作

#### 创建活动设计

```http
POST /api/activity-designs
```

请求体：
```json
{
    "title": "活动标题",
    "subject": "学科",
    "grade": "年级",
    "duration": 45,
    "objectives": ["目标1", "目标2"],
    "materials": ["材料1", "材料2"],
    "procedure": "活动步骤",
    "assessment": "评估方式",
    "status": "draft"
}
```

响应：
```json
{
    "id": 1,
    "title": "活动标题",
    "subject": "学科",
    "grade": "年级",
    "duration": 45,
    "objectives": ["目标1", "目标2"],
    "materials": ["材料1", "材料2"],
    "procedure": "活动步骤",
    "assessment": "评估方式",
    "status": "draft",
    "created_at": "2024-01-01 12:00:00",
    "updated_at": "2024-01-01 12:00:00"
}
```

#### 获取单个活动设计

```http
GET /api/activity-designs/{id}
```

响应：
```json
{
    "id": 1,
    "title": "活动标题",
    "subject": "学科",
    "grade": "年级",
    "duration": 45,
    "objectives": ["目标1", "目标2"],
    "materials": ["材料1", "材料2"],
    "procedure": "活动步骤",
    "assessment": "评估方式",
    "status": "draft",
    "created_at": "2024-01-01 12:00:00",
    "updated_at": "2024-01-01 12:00:00"
}
```

#### 获取用户的活动设计列表

```http
GET /api/activity-designs/user/list?limit=10
```

查询参数：
- `limit`: 返回的最大记录数（默认：10）

响应：
```json
{
    "data": [
        {
            "id": 1,
            "title": "活动标题",
            "subject": "学科",
            "grade": "年级",
            "duration": 45,
            "status": "draft",
            "created_at": "2024-01-01 12:00:00"
        }
    ],
    "total": 1
}
```

#### 更新活动设计

```http
PUT /api/activity-designs/{id}
```

请求体：
```json
{
    "title": "更新后的标题",
    "subject": "更新后的学科",
    "grade": "更新后的年级",
    "duration": 60,
    "objectives": ["更新后的目标1", "更新后的目标2"],
    "materials": ["更新后的材料1", "更新后的材料2"],
    "procedure": "更新后的活动步骤",
    "assessment": "更新后的评估方式",
    "status": "published"
}
```

响应：
```json
{
    "id": 1,
    "title": "更新后的标题",
    "subject": "更新后的学科",
    "grade": "更新后的年级",
    "duration": 60,
    "objectives": ["更新后的目标1", "更新后的目标2"],
    "materials": ["更新后的材料1", "更新后的材料2"],
    "procedure": "更新后的活动步骤",
    "assessment": "更新后的评估方式",
    "status": "published",
    "created_at": "2024-01-01 12:00:00",
    "updated_at": "2024-01-01 13:00:00"
}
```

#### 删除活动设计

```http
DELETE /api/activity-designs/{id}
```

响应：
```json
{
    "success": true
}
```

### 搜索和模板

#### 搜索活动设计

```http
GET /api/activity-designs/search?keyword=关键词&limit=10
```

查询参数：
- `keyword`: 搜索关键词
- `limit`: 返回的最大记录数（默认：10）

响应：
```json
{
    "data": [
        {
            "id": 1,
            "title": "包含关键词的活动标题",
            "subject": "学科",
            "grade": "年级",
            "duration": 45,
            "status": "draft",
            "created_at": "2024-01-01 12:00:00"
        }
    ],
    "total": 1
}
```

#### 获取活动设计模板

```http
GET /api/activity-designs/templates?limit=10
```

查询参数：
- `limit`: 返回的最大记录数（默认：10）

响应：
```json
{
    "data": [
        {
            "id": 1,
            "title": "模板标题",
            "subject": "学科",
            "grade": "年级",
            "duration": 45,
            "status": "template",
            "created_at": "2024-01-01 12:00:00"
        }
    ],
    "total": 1
}
```

#### 复制活动设计

```http
POST /api/activity-designs/{id}/duplicate
```

响应：
```json
{
    "id": 2,
    "title": "活动标题 (副本)",
    "subject": "学科",
    "grade": "年级",
    "duration": 45,
    "objectives": ["目标1", "目标2"],
    "materials": ["材料1", "材料2"],
    "procedure": "活动步骤",
    "assessment": "评估方式",
    "status": "draft",
    "created_at": "2024-01-01 14:00:00",
    "updated_at": "2024-01-01 14:00:00"
}
```

### 状态管理

#### 更新活动设计状态

```http
PUT /api/activity-designs/{id}/status
```

请求体：
```json
{
    "status": "published"
}
```

响应：
```json
{
    "id": 1,
    "status": "published",
    "updated_at": "2024-01-01 15:00:00"
}
```

### AI 辅助功能

#### 生成活动设计建议

```http
POST /api/activity-designs/generate-suggestions
```

请求体：
```json
{
    "title": "活动标题",
    "subject": "学科",
    "grade": "年级",
    "duration": 45
}
```

响应：
```json
{
    "suggestions": {
        "objectives": ["建议的目标1", "建议的目标2"],
        "materials": ["建议的材料1", "建议的材料2"],
        "procedure": ["建议的步骤1", "建议的步骤2"],
        "assessment": ["建议的评估方式1", "建议的评估方式2"]
    },
    "original_data": {
        "title": "活动标题",
        "subject": "学科",
        "grade": "年级",
        "duration": 45
    }
}
```

### 导入导出

#### 导出活动设计

```http
GET /api/activity-designs/{id}/export
```

响应：
```json
{
    "filename": "activity_design_1_20240101120000.json",
    "url": "http://example.com/exports/activity_design_1_20240101120000.json"
}
```

#### 导入活动设计

```http
POST /api/activity-designs/import
```

请求体：
```json
{
    "title": "导入的活动标题",
    "subject": "学科",
    "grade": "年级",
    "duration": 45,
    "objectives": ["目标1", "目标2"],
    "materials": ["材料1", "材料2"],
    "procedure": "活动步骤",
    "assessment": "评估方式",
    "status": "draft"
}
```

响应：
```json
{
    "id": 3,
    "title": "导入的活动标题",
    "subject": "学科",
    "grade": "年级",
    "duration": 45,
    "objectives": ["目标1", "目标2"],
    "materials": ["材料1", "材料2"],
    "procedure": "活动步骤",
    "assessment": "评估方式",
    "status": "draft",
    "created_at": "2024-01-01 16:00:00",
    "updated_at": "2024-01-01 16:00:00"
}
```

## 错误响应

所有 API 端点都可能返回以下错误响应：

```json
{
    "error": "错误消息"
}
```

常见 HTTP 状态码：
- 400: 请求参数错误
- 401: 未认证
- 403: 无权限
- 404: 资源不存在
- 500: 服务器内部错误

## 注意事项

1. 所有时间戳都使用 ISO 8601 格式
2. 所有 ID 都是整数
3. 所有文本字段都支持 UTF-8 编码
4. 数组字段可以为空
5. 状态字段的可用值：draft, published, archived 