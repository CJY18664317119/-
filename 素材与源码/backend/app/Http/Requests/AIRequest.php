<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AIRequest extends FormRequest
{
    /**
     * 确定用户是否有权限进行此请求
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 获取验证规则
     */
    public function rules(): array
    {
        $rules = [];

        // 教案生成验证规则
        if ($this->is('api/ai/generate-lesson-plan')) {
            $rules = [
                'title' => 'required|string|max:255',
                'subject' => 'required|string|max:100',
                'grade' => 'required|string|max:50',
                'duration' => 'required|integer|min:1|max:480',
                'objectives' => 'required|array',
                'objectives.*' => 'string|max:500',
                'content' => 'required|string'
            ];
        }

        // 课件生成验证规则
        if ($this->is('api/ai/generate-courseware')) {
            $rules = [
                'title' => 'required|string|max:255',
                'subject' => 'required|string|max:100',
                'grade' => 'required|string|max:50',
                'content' => 'required|string',
                'format' => 'required|string|in:ppt,pdf'
            ];
        }

        // 文章分析验证规则
        if ($this->is('api/ai/analyze-article')) {
            $rules = [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'language' => 'required|string|in:zh-CN,en-US'
            ];
        }

        // 作文批改验证规则
        if ($this->is('api/ai/correct-essay')) {
            $rules = [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'grade' => 'required|string|max:50',
                'language' => 'required|string|in:zh-CN,en-US'
            ];
        }

        // 聊天功能验证规则
        if ($this->is('api/ai/chat')) {
            $rules = [
                'message' => 'required|string',
                'chat_id' => 'nullable|string',
                'context' => 'nullable|array',
                'context.subject' => 'nullable|string|max:100',
                'context.grade' => 'nullable|string|max:50',
                'context.topic' => 'nullable|string|max:255'
            ];
        }

        return $rules;
    }

    /**
     * 获取验证错误的自定义消息
     */
    public function messages(): array
    {
        return [
            // 通用消息
            'title.required' => '标题不能为空',
            'title.max' => '标题不能超过255个字符',
            'subject.required' => '学科不能为空',
            'subject.max' => '学科不能超过100个字符',
            'grade.required' => '年级不能为空',
            'grade.max' => '年级不能超过50个字符',
            'content.required' => '内容不能为空',
            'language.required' => '语言不能为空',
            'language.in' => '语言必须是zh-CN或en-US',

            // 教案生成消息
            'duration.required' => '时长不能为空',
            'duration.integer' => '时长必须是整数',
            'duration.min' => '时长不能小于1分钟',
            'duration.max' => '时长不能超过480分钟',
            'objectives.required' => '目标不能为空',
            'objectives.array' => '目标必须是数组',
            'objectives.*.string' => '每个目标必须是字符串',
            'objectives.*.max' => '每个目标不能超过500个字符',

            // 课件生成消息
            'format.required' => '格式不能为空',
            'format.in' => '格式必须是ppt或pdf',

            // 聊天功能消息
            'message.required' => '消息不能为空',
            'chat_id.string' => '聊天ID必须是字符串',
            'context.array' => '上下文必须是数组',
            'context.subject.max' => '学科不能超过100个字符',
            'context.grade.max' => '年级不能超过50个字符',
            'context.topic.max' => '主题不能超过255个字符'
        ];
    }
} 