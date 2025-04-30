# AI 功能 API 文档

## 概述

AI 功能 API 提供了一系列端点，用于访问 AI 辅助功能，包括生成教案、课件、文章分析、作文批改以及聊天功能。

## 基础路径

所有 API 端点都以 `/api/ai` 为基础路径。

## 认证

所有 API 端点都需要通过 Bearer Token 进行认证。请在请求头中添加：

```
Authorization: Bearer {token}
```

## 端点列表

### 教案生成

#### 生成教案

```http
POST /api/ai/generate-lesson-plan
```

请求体：
```json
{
    "title": "课程标题",
    "subject": "学科",
    "grade": "年级",
    "duration": 45,
    "objectives": ["目标1", "目标2"],
    "content": "课程内容"
}
```

响应：
```json
{
    "lesson_plan": {
        "title": "课程标题",
        "subject": "学科",
        "grade": "年级",
        "duration": 45,
        "objectives": ["目标1", "目标2"],
        "content": "课程内容",
        "activities": ["活动1", "活动2"],
        "assessment": "评估方式",
        "resources": ["资源1", "资源2"]
    },
    "generated_at": "2024-01-01 12:00:00"
}
```

### 课件生成

#### 生成课件

```http
POST /api/ai/generate-courseware
```

请求体：
```json
{
    "title": "课件标题",
    "subject": "学科",
    "grade": "年级",
    "content": "课件内容",
    "format": "ppt"
}
```

响应：
```json
{
    "courseware": {
        "title": "课件标题",
        "subject": "学科",
        "grade": "年级",
        "content": "课件内容",
        "format": "ppt",
        "slides": [
            {
                "title": "第一页",
                "content": "第一页内容"
            },
            {
                "title": "第二页",
                "content": "第二页内容"
            }
        ]
    },
    "download_url": "http://example.com/downloads/courseware_1_20240101120000.pptx",
    "generated_at": "2024-01-01 12:00:00"
}
```

### 文章分析

#### 分析文章

```http
POST /api/ai/analyze-article
```

请求体：
```json
{
    "title": "文章标题",
    "content": "文章内容",
    "language": "zh-CN"
}
```

响应：
```json
{
    "analysis": {
        "summary": "文章摘要",
        "key_points": ["要点1", "要点2"],
        "vocabulary": ["词汇1", "词汇2"],
        "grammar_notes": ["语法点1", "语法点2"],
        "reading_level": "中级",
        "suggested_activities": ["活动1", "活动2"]
    },
    "analyzed_at": "2024-01-01 12:00:00"
}
```

### 作文批改

#### 批改作文

```http
POST /api/ai/correct-essay
```

请求体：
```json
{
    "title": "作文标题",
    "content": "作文内容",
    "grade": "年级",
    "language": "zh-CN"
}
```

响应：
```json
{
    "correction": {
        "content": "修改后的作文内容",
        "comments": ["评语1", "评语2"],
        "grammar_errors": ["错误1", "错误2"],
        "vocabulary_suggestions": ["建议1", "建议2"],
        "score": 85,
        "grade": "B+"
    },
    "corrected_at": "2024-01-01 12:00:00"
}
```

### 聊天功能

#### 发送消息

```http
POST /api/ai/chat
```

请求体：
```json
{
    "message": "用户消息",
    "chat_id": "可选，聊天ID",
    "context": {
        "subject": "学科",
        "grade": "年级",
        "topic": "主题"
    }
}
```

响应：
```json
{
    "response": "AI回复",
    "chat_id": "聊天ID",
    "timestamp": "2024-01-01 12:00:00"
}
```

#### 获取聊天历史

```http
GET /api/ai/chat-history?limit=10
```

查询参数：
- `limit`: 返回的最大记录数（默认：10）

响应：
```json
{
    "chats": [
        {
            "id": "聊天ID",
            "title": "聊天标题",
            "last_message": "最后一条消息",
            "updated_at": "2024-01-01 12:00:00"
        }
    ],
    "total": 1
}
```

#### 获取单个聊天

```http
GET /api/ai/chat/{id}
```

响应：
```json
{
    "id": "聊天ID",
    "title": "聊天标题",
    "messages": [
        {
            "role": "user",
            "content": "用户消息",
            "timestamp": "2024-01-01 12:00:00"
        },
        {
            "role": "assistant",
            "content": "AI回复",
            "timestamp": "2024-01-01 12:01:00"
        }
    ],
    "created_at": "2024-01-01 12:00:00",
    "updated_at": "2024-01-01 12:01:00"
}
```

#### 删除聊天

```http
DELETE /api/ai/chat/{id}
```

响应：
```json
{
    "success": true
}
```

#### 保存聊天

```http
POST /api/ai/save-chat
```

请求体：
```json
{
    "chat_id": "聊天ID",
    "title": "聊天标题"
}
```

响应：
```json
{
    "success": true,
    "chat_id": "聊天ID",
    "title": "聊天标题",
    "saved_at": "2024-01-01 12:00:00"
}
```

#### 导出聊天

```http
GET /api/ai/export-chat/{id}
```

响应：
```json
{
    "filename": "chat_1_20240101120000.json",
    "url": "http://example.com/exports/chat_1_20240101120000.json"
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
2. 所有文本字段都支持 UTF-8 编码
3. 聊天 ID 可以是字符串或整数
4. 文件下载链接有效期通常为 24 小时
5. AI 生成的内容可能需要 5-30 秒的处理时间
6. 建议在客户端实现适当的重试机制
7. 对于大文件生成（如课件），建议使用异步处理 