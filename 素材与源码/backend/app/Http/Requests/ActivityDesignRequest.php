<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityDesignRequest extends FormRequest
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
            'materials' => 'required|array',
            'materials.*' => 'string|max:500',
            'procedure' => 'required|string',
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
            'title.required' => '活动标题不能为空',
            'title.max' => '活动标题不能超过255个字符',
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
            'materials.required' => '材料不能为空',
            'materials.array' => '材料必须是数组',
            'materials.*.string' => '每个材料必须是字符串',
            'materials.*.max' => '每个材料不能超过500个字符',
            'procedure.required' => '活动步骤不能为空',
            'assessment.required' => '评估方式不能为空',
            'status.required' => '状态不能为空',
            'status.in' => '状态必须是draft、published或archived之一'
        ];
    }
} 