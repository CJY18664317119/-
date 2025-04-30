<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Collaboration;

class CollaborationRequest extends FormRequest
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
            'resource_type' => 'required|string|in:activity,project,lesson,courseware',
            'resource_id' => 'required|integer',
            'collaborator_id' => 'required|integer|exists:users,id',
            'permission' => 'required|string|in:view,edit,admin',
            'notes' => 'nullable|array',
            'notes.*' => 'string|max:500'
        ];
    }

    /**
     * 获取验证错误的自定义消息
     */
    public function messages(): array
    {
        return [
            'resource_type.required' => '资源类型不能为空',
            'resource_type.in' => '资源类型必须是activity、project、lesson或courseware',
            'resource_id.required' => '资源ID不能为空',
            'resource_id.integer' => '资源ID必须是整数',
            'collaborator_id.required' => '协作者ID不能为空',
            'collaborator_id.integer' => '协作者ID必须是整数',
            'collaborator_id.exists' => '协作者不存在',
            'permission.required' => '权限不能为空',
            'permission.in' => '权限必须是view、edit或admin',
            'notes.array' => '备注必须是数组',
            'notes.*.string' => '每个备注必须是字符串',
            'notes.*.max' => '每个备注不能超过500个字符'
        ];
    }

    /**
     * 准备验证数据
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'owner_id' => $this->user()->id,
            'status' => Collaboration::STATUS_PENDING
        ]);
    }
} 