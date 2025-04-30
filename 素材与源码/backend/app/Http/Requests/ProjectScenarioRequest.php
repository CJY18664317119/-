<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectScenarioRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:255',
            'subject' => 'required|string|max:100',
            'grade' => 'required|string|max:50',
            'duration' => 'required|integer|min:1|max:480',
            'objectives' => 'required|array',
            'objectives.*' => 'string|max:500',
            'background' => 'required|string',
            'tasks' => 'required|array',
            'tasks.*' => 'string|max:500',
            'resources' => 'required|array',
            'resources.*' => 'string|max:500',
            'assessment' => 'required|string',
            'status' => 'required|string|in:draft,published,archived'
        ];
    }

    /**
     * 获取验证错误的自定义消息
     */
    public function messages(): array
    {
        return [
            'title.required' => '项目标题不能为空',
            'title.max' => '项目标题不能超过255个字符',
            'subject.required' => '学科不能为空',
            'subject.max' => '学科不能超过100个字符',
            'grade.required' => '年级不能为空',
            'grade.max' => '年级不能超过50个字符',
            'duration.required' => '时长不能为空',
            'duration.integer' => '时长必须是整数',
            'duration.min' => '时长不能小于1分钟',
            'duration.max' => '时长不能超过480分钟',
            'objectives.required' => '目标不能为空',
            'objectives.array' => '目标必须是数组',
            'objectives.*.string' => '每个目标必须是字符串',
            'objectives.*.max' => '每个目标不能超过500个字符',
            'background.required' => '项目背景不能为空',
            'tasks.required' => '任务不能为空',
            'tasks.array' => '任务必须是数组',
            'tasks.*.string' => '每个任务必须是字符串',
            'tasks.*.max' => '每个任务不能超过500个字符',
            'resources.required' => '资源不能为空',
            'resources.array' => '资源必须是数组',
            'resources.*.string' => '每个资源必须是字符串',
            'resources.*.max' => '每个资源不能超过500个字符',
            'assessment.required' => '评估方式不能为空',
            'status.required' => '状态不能为空',
            'status.in' => '状态必须是draft、published或archived之一'
        ];
    }
} 