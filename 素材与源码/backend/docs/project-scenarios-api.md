# 项目情境 API 文档

## 概述

项目情境 API 提供了一系列端点，用于管理项目情境的创建、更新、删除、搜索等操作，以及使用 AI 生成项目情境的各个组成部分。

## 基础路径

所有 API 端点都以 `/api/project-scenarios` 为基础路径。

## 认证

所有 API 端点都需要通过 Bearer Token 进行认证。请在请求头中添加：

```
Authorization: Bearer {token}
```

## 端点列表

### 基本 CRUD 操作

#### 创建项目情境

```http
POST /api/project-scenarios
```

请求体：
```json
{
    "title": "项目标题",
    "subject": "学科",
    "grade": "年级",
    "duration": 45,
    "objectives": ["目标1", "目标2"],
    "background": "项目背景",
    "tasks": ["任务1", "任务2"],
    "resources": ["资源1", "资源2"],
    "assessment": "评估方式",
    "status": "draft"
}
```

响应：
```json
{
    "id": 1,
    "title": "项目标题",
    "subject": "学科",
    "grade": "年级",
    "duration": 45,
    "objectives": ["目标1", "目标2"],
    "background": "项目背景",
    "tasks": ["任务1", "任务2"],
    "resources": ["资源1", "资源2"],
    "assessment": "评估方式",
    "status": "draft",
    "created_at": "2024-01-01 12:00:00",
    "updated_at": "2024-01-01 12:00:00"
}
```

#### 获取单个项目情境

```http
GET /api/project-scenarios/{id}
```

响应：
```json
{
    "id": 1,
    "title": "项目标题",
    "subject": "学科",
    "grade": "年级",
    "duration": 45,
    "objectives": ["目标1", "目标2"],
    "background": "项目背景",
    "tasks": ["任务1", "任务2"],
    "resources": ["资源1", "资源2"],
    "assessment": "评估方式",
    "status": "draft",
    "created_at": "2024-01-01 12:00:00",
    "updated_at": "2024-01-01 12:00:00"
}
```

#### 获取用户的项目情境列表

```http
GET /api/project-scenarios/user/list?limit=10
```

查询参数：
- `limit`: 返回的最大记录数（默认：10）

响应：
```json
{
    "data": [
        {
            "id": 1,
            "title": "项目标题",
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

#### 更新项目情境

```http
PUT /api/project-scenarios/{id}
```

请求体：
```json
{
    "title": "更新后的标题",
    "subject": "更新后的学科",
    "grade": "更新后的年级",
    "duration": 60,
    "objectives": ["更新后的目标1", "更新后的目标2"],
    "background": "更新后的背景",
    "tasks": ["更新后的任务1", "更新后的任务2"],
    "resources": ["更新后的资源1", "更新后的资源2"],
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
    "background": "更新后的背景",
    "tasks": ["更新后的任务1", "更新后的任务2"],
    "resources": ["更新后的资源1", "更新后的资源2"],
    "assessment": "更新后的评估方式",
    "status": "published",
    "created_at": "2024-01-01 12:00:00",
    "updated_at": "2024-01-01 13:00:00"
}
```

#### 删除项目情境

```http
DELETE /api/project-scenarios/{id}
```

响应：
```json
{
    "success": true
}
```

### 搜索和模板

#### 搜索项目情境

```http
GET /api/project-scenarios/search?keyword=关键词&limit=10
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
            "title": "包含关键词的项目标题",
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

#### 获取项目情境模板

```http
GET /api/project-scenarios/templates?limit=10
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

#### 复制项目情境

```http
POST /api/project-scenarios/{id}/duplicate
```

响应：
```json
{
    "id": 2,
    "title": "项目标题 (副本)",
    "subject": "学科",
    "grade": "年级",
    "duration": 45,
    "objectives": ["目标1", "目标2"],
    "background": "项目背景",
    "tasks": ["任务1", "任务2"],
    "resources": ["资源1", "资源2"],
    "assessment": "评估方式",
    "status": "draft",
    "created_at": "2024-01-01 14:00:00",
    "updated_at": "2024-01-01 14:00:00"
}
```

### 状态管理

#### 更新项目情境状态

```http
PUT /api/project-scenarios/{id}/status
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

#### 生成项目情境建议

```http
POST /api/project-scenarios/generate-suggestions
```

请求体：
```json
{
    "title": "项目标题",
    "subject": "学科",
    "grade": "年级",
    "duration": 45
}
```

响应：
```json
{
    "suggestions": {
        "background": ["建议的背景1", "建议的背景2"],
        "objectives": ["建议的目标1", "建议的目标2"],
        "tasks": ["建议的任务1", "建议的任务2"],
        "resources": ["建议的资源1", "建议的资源2"],
        "assessment": ["建议的评估方式1", "建议的评估方式2"]
    },
    "original_data": {
        "title": "项目标题",
        "subject": "学科",
        "grade": "年级",
        "duration": 45
    }
}
```

#### 生成项目背景

```http
POST /api/project-scenarios/generate-background
```

请求体：
```json
{
    "title": "项目标题",
    "subject": "学科",
    "grade": "年级",
    "duration": 45
}
```

响应：
```json
{
    "background": "生成的详细项目背景描述...",
    "original_data": {
        "title": "项目标题",
        "subject": "学科",
        "grade": "年级",
        "duration": 45
    }
}
```

#### 生成项目任务

```http
POST /api/project-scenarios/generate-tasks
```

请求体：
```json
{
    "title": "项目标题",
    "subject": "学科",
    "grade": "年级",
    "background": "项目背景"
}
```

响应：
```json
{
    "tasks": ["生成的任务1", "生成的任务2", "生成的任务3"],
    "original_data": {
        "title": "项目标题",
        "subject": "学科",
        "grade": "年级",
        "background": "项目背景"
    }
}
```

#### 生成项目资源

```http
POST /api/project-scenarios/generate-resources
```

请求体：
```json
{
    "title": "项目标题",
    "subject": "学科",
    "grade": "年级",
    "tasks": ["任务1", "任务2", "任务3"]
}
```

响应：
```json
{
    "resources": ["生成的资源1", "生成的资源2", "生成的资源3"],
    "original_data": {
        "title": "项目标题",
        "subject": "学科",
        "grade": "年级",
        "tasks": ["任务1", "任务2", "任务3"]
    }
}
```

#### 生成项目评估

```http
POST /api/project-scenarios/generate-assessment
```

请求体：
```json
{
    "title": "项目标题",
    "subject": "学科",
    "grade": "年级",
    "objectives": ["目标1", "目标2", "目标3"]
}
```

响应：
```json
{
    "assessment": "生成的详细评估方案...",
    "original_data": {
        "title": "项目标题",
        "subject": "学科",
        "grade": "年级",
        "objectives": ["目标1", "目标2", "目标3"]
    }
}
```

### 导入导出

#### 导出项目情境

```http
GET /api/project-scenarios/{id}/export
```

响应：
```json
{
    "filename": "project_scenario_1_20240101120000.json",
    "url": "http://example.com/exports/project_scenario_1_20240101120000.json"
}
```

#### 导入项目情境

```http
POST /api/project-scenarios/import
```

请求体：
```json
{
    "title": "导入的项目标题",
    "subject": "学科",
    "grade": "年级",
    "duration": 45,
    "objectives": ["目标1", "目标2"],
    "background": "项目背景",
    "tasks": ["任务1", "任务2"],
    "resources": ["资源1", "资源2"],
    "assessment": "评估方式",
    "status": "draft"
}
```

响应：
```json
{
    "id": 3,
    "title": "导入的项目标题",
    "subject": "学科",
    "grade": "年级",
    "duration": 45,
    "objectives": ["目标1", "目标2"],
    "background": "项目背景",
    "tasks": ["任务1", "任务2"],
    "resources": ["资源1", "资源2"],
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