<?php

namespace App\Services\Impl;

use App\Contracts\Repositories\ActivityDesignRepositoryInterface;
use App\Contracts\Services\ActivityDesignServiceInterface;
use App\Contracts\Services\AIServiceInterface;
use Illuminate\Support\Facades\Storage;
use App\Events\ActivityDesignCreated;
use App\Events\ActivityDesignUpdated;
use App\Events\ActivityDesignStatusChanged;

class ActivityDesignServiceImpl implements ActivityDesignServiceInterface
{
    protected $repository;
    protected $aiService;

    public function __construct(
        ActivityDesignRepositoryInterface $repository,
        AIServiceInterface $aiService
    ) {
        $this->repository = $repository;
        $this->aiService = $aiService;
    }

    public function createDesign(array $data): array
    {
        $design = $this->repository->create($data);
        
        // 触发创建事件
        event(new ActivityDesignCreated($design));
        
        return $design->toArray();
    }

    public function getDesign(int $id): array
    {
        $design = $this->repository->find($id);
        return $design->toArray();
    }

    public function getUserDesigns(int $userId, int $limit = 10): array
    {
        $designs = $this->repository->getUserDesigns($userId, $limit);
        return $designs->toArray();
    }

    public function updateDesign(int $id, array $data): array
    {
        $oldDesign = $this->repository->find($id);
        $design = $this->repository->update($id, $data);
        
        // 计算变更的字段
        $changes = [];
        foreach ($data as $key => $value) {
            if ($oldDesign->$key != $value) {
                $changes[$key] = [
                    'old' => $oldDesign->$key,
                    'new' => $value
                ];
            }
        }
        
        // 触发更新事件
        if (!empty($changes)) {
            event(new ActivityDesignUpdated($design, $changes));
        }
        
        return $design->toArray();
    }

    public function deleteDesign(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function searchDesigns(int $userId, string $keyword, int $limit = 10): array
    {
        $designs = $this->repository->search($userId, $keyword, $limit);
        return $designs->toArray();
    }

    public function getTemplates(int $limit = 10): array
    {
        $templates = $this->repository->getTemplates($limit);
        return $templates->toArray();
    }

    public function duplicateDesign(int $id, int $userId): array
    {
        $design = $this->repository->duplicate($id, $userId);
        return $design->toArray();
    }

    public function updateDesignStatus(int $id, string $status): array
    {
        $design = $this->repository->find($id);
        $oldStatus = $design->status;
        
        $design = $this->repository->updateStatus($id, $status);
        
        // 触发状态变更事件
        if ($oldStatus != $status) {
            event(new ActivityDesignStatusChanged($design, $oldStatus, $status));
        }
        
        return $design->toArray();
    }

    public function generateSuggestions(array $data): array
    {
        // 使用 AI 服务生成建议
        $prompt = $this->buildSuggestionPrompt($data);
        $response = $this->aiService->chat($prompt);
        
        return [
            'suggestions' => $this->parseAISuggestions($response),
            'original_data' => $data
        ];
    }

    public function exportDesign(int $id): array
    {
        $design = $this->repository->find($id);
        
        // 生成导出文件
        $filename = "activity_design_{$id}_" . now()->format('YmdHis') . '.json';
        $content = json_encode($design->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        // 保存文件
        Storage::disk('exports')->put($filename, $content);
        
        return [
            'filename' => $filename,
            'url' => Storage::disk('exports')->url($filename)
        ];
    }

    public function importDesign(array $data, int $userId): array
    {
        // 添加用户 ID
        $data['user_id'] = $userId;
        
        // 创建新的活动设计
        $design = $this->repository->create($data);
        return $design->toArray();
    }

    protected function buildSuggestionPrompt(array $data): string
    {
        $prompt = "请为以下教学活动设计提供建议：\n\n";
        $prompt .= "标题：{$data['title']}\n";
        $prompt .= "学科：{$data['subject']}\n";
        $prompt .= "年级：{$data['grade']}\n";
        $prompt .= "时长：{$data['duration']}分钟\n\n";
        
        $prompt .= "教学目标：\n";
        foreach ($data['objectives'] as $objective) {
            $prompt .= "- {$objective}\n";
        }
        
        $prompt .= "\n请为以下四个阶段提供具体建议：\n";
        $prompt .= "1. 热身阶段 (Warm-up)\n";
        $prompt .= "2. 呈现阶段 (Presentation)\n";
        $prompt .= "3. 练习阶段 (Practice)\n";
        $prompt .= "4. 产出阶段 (Production)\n";
        
        return $prompt;
    }

    protected function parseAISuggestions(array $response): array
    {
        // 解析 AI 返回的建议
        $suggestions = [];
        
        // 假设 AI 返回的是结构化的 JSON 数据
        if (isset($response['suggestions'])) {
            $suggestions = $response['suggestions'];
        } else {
            // 如果返回的是文本，尝试解析
            $content = $response['content'] ?? '';
            $suggestions = $this->parseTextSuggestions($content);
        }
        
        return $suggestions;
    }

    protected function parseTextSuggestions(string $content): array
    {
        $suggestions = [
            'warm_up' => [],
            'presentation' => [],
            'practice' => [],
            'production' => []
        ];
        
        // 简单的文本解析逻辑
        $lines = explode("\n", $content);
        $currentStage = null;
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            if (empty($line)) continue;
            
            // 检测阶段标题
            if (strpos($line, '热身阶段') !== false) {
                $currentStage = 'warm_up';
            } elseif (strpos($line, '呈现阶段') !== false) {
                $currentStage = 'presentation';
            } elseif (strpos($line, '练习阶段') !== false) {
                $currentStage = 'practice';
            } elseif (strpos($line, '产出阶段') !== false) {
                $currentStage = 'production';
            }
            
            // 添加建议
            if ($currentStage && strpos($line, '-') === 0) {
                $suggestion = trim(substr($line, 1));
                if (!empty($suggestion)) {
                    $suggestions[$currentStage][] = $suggestion;
                }
            }
        }
        
        return $suggestions;
    }
} 