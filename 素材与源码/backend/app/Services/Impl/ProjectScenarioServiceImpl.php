<?php

namespace App\Services\Impl;

use App\Contracts\Repositories\ProjectScenarioRepositoryInterface;
use App\Contracts\Services\ProjectScenarioServiceInterface;
use App\Contracts\Services\AIServiceInterface;
use Illuminate\Support\Facades\Storage;

class ProjectScenarioServiceImpl implements ProjectScenarioServiceInterface
{
    protected $repository;
    protected $aiService;

    public function __construct(
        ProjectScenarioRepositoryInterface $repository,
        AIServiceInterface $aiService
    ) {
        $this->repository = $repository;
        $this->aiService = $aiService;
    }

    public function createScenario(array $data): array
    {
        $scenario = $this->repository->create($data);
        return $scenario->toArray();
    }

    public function getScenario(int $id): array
    {
        $scenario = $this->repository->find($id);
        return $scenario->toArray();
    }

    public function getUserScenarios(int $userId, int $limit = 10): array
    {
        $scenarios = $this->repository->getUserScenarios($userId, $limit);
        return $scenarios->toArray();
    }

    public function updateScenario(int $id, array $data): array
    {
        $scenario = $this->repository->update($id, $data);
        return $scenario->toArray();
    }

    public function deleteScenario(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function searchScenarios(int $userId, string $keyword, int $limit = 10): array
    {
        $scenarios = $this->repository->search($userId, $keyword, $limit);
        return $scenarios->toArray();
    }

    public function getTemplates(int $limit = 10): array
    {
        $templates = $this->repository->getTemplates($limit);
        return $templates->toArray();
    }

    public function duplicateScenario(int $id, int $userId): array
    {
        $scenario = $this->repository->duplicate($id, $userId);
        return $scenario->toArray();
    }

    public function updateScenarioStatus(int $id, string $status): array
    {
        $scenario = $this->repository->updateStatus($id, $status);
        return $scenario->toArray();
    }

    public function generateSuggestions(array $data): array
    {
        $prompt = $this->buildSuggestionPrompt($data);
        $response = $this->aiService->chat($prompt);
        
        return [
            'suggestions' => $this->parseAISuggestions($response),
            'original_data' => $data
        ];
    }

    public function exportScenario(int $id): array
    {
        $scenario = $this->repository->find($id);
        
        $filename = "project_scenario_{$id}_" . now()->format('YmdHis') . '.json';
        $content = json_encode($scenario->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        Storage::disk('exports')->put($filename, $content);
        
        return [
            'filename' => $filename,
            'url' => Storage::disk('exports')->url($filename)
        ];
    }

    public function importScenario(array $data, int $userId): array
    {
        $data['user_id'] = $userId;
        $scenario = $this->repository->import($data, $userId);
        return $scenario->toArray();
    }

    public function generateBackground(array $data): array
    {
        $prompt = $this->buildBackgroundPrompt($data);
        $response = $this->aiService->chat($prompt);
        
        return [
            'background' => $response['content'] ?? '',
            'original_data' => $data
        ];
    }

    public function generateTasks(array $data): array
    {
        $prompt = $this->buildTasksPrompt($data);
        $response = $this->aiService->chat($prompt);
        
        return [
            'tasks' => $this->parseTasks($response),
            'original_data' => $data
        ];
    }

    public function generateResources(array $data): array
    {
        $prompt = $this->buildResourcesPrompt($data);
        $response = $this->aiService->chat($prompt);
        
        return [
            'resources' => $this->parseResources($response),
            'original_data' => $data
        ];
    }

    public function generateAssessment(array $data): array
    {
        $prompt = $this->buildAssessmentPrompt($data);
        $response = $this->aiService->chat($prompt);
        
        return [
            'assessment' => $this->parseAssessment($response),
            'original_data' => $data
        ];
    }

    protected function buildSuggestionPrompt(array $data): string
    {
        $prompt = "请为以下项目情境提供建议：\n\n";
        $prompt .= "标题：{$data['title']}\n";
        $prompt .= "学科：{$data['subject']}\n";
        $prompt .= "年级：{$data['grade']}\n";
        $prompt .= "时长：{$data['duration']}分钟\n\n";
        
        $prompt .= "请为以下方面提供具体建议：\n";
        $prompt .= "1. 项目背景\n";
        $prompt .= "2. 项目目标\n";
        $prompt .= "3. 项目任务\n";
        $prompt .= "4. 所需资源\n";
        $prompt .= "5. 评估方式\n";
        
        return $prompt;
    }

    protected function buildBackgroundPrompt(array $data): string
    {
        $prompt = "请为以下项目情境生成背景描述：\n\n";
        $prompt .= "标题：{$data['title']}\n";
        $prompt .= "学科：{$data['subject']}\n";
        $prompt .= "年级：{$data['grade']}\n";
        $prompt .= "时长：{$data['duration']}分钟\n\n";
        
        $prompt .= "请生成一个真实、有趣且适合该年级学生的项目背景。";
        
        return $prompt;
    }

    protected function buildTasksPrompt(array $data): string
    {
        $prompt = "请为以下项目情境生成任务列表：\n\n";
        $prompt .= "标题：{$data['title']}\n";
        $prompt .= "学科：{$data['subject']}\n";
        $prompt .= "年级：{$data['grade']}\n";
        $prompt .= "背景：{$data['background']}\n\n";
        
        $prompt .= "请生成3-5个具体的项目任务，每个任务应该：\n";
        $prompt .= "1. 清晰明确\n";
        $prompt .= "2. 具有挑战性但可实现\n";
        $prompt .= "3. 与项目目标相关\n";
        $prompt .= "4. 适合该年级学生的能力水平\n";
        
        return $prompt;
    }

    protected function buildResourcesPrompt(array $data): string
    {
        $prompt = "请为以下项目情境生成资源列表：\n\n";
        $prompt .= "标题：{$data['title']}\n";
        $prompt .= "学科：{$data['subject']}\n";
        $prompt .= "年级：{$data['grade']}\n";
        $prompt .= "任务：\n";
        foreach ($data['tasks'] as $task) {
            $prompt .= "- {$task}\n";
        }
        $prompt .= "\n请生成完成这些任务所需的资源列表，包括：\n";
        $prompt .= "1. 材料资源\n";
        $prompt .= "2. 人力资源\n";
        $prompt .= "3. 时间资源\n";
        $prompt .= "4. 其他必要资源\n";
        
        return $prompt;
    }

    protected function buildAssessmentPrompt(array $data): string
    {
        $prompt = "请为以下项目情境生成评估方案：\n\n";
        $prompt .= "标题：{$data['title']}\n";
        $prompt .= "学科：{$data['subject']}\n";
        $prompt .= "年级：{$data['grade']}\n";
        $prompt .= "目标：\n";
        foreach ($data['objectives'] as $objective) {
            $prompt .= "- {$objective}\n";
        }
        $prompt .= "\n请生成一个全面的评估方案，包括：\n";
        $prompt .= "1. 评估标准\n";
        $prompt .= "2. 评估方法\n";
        $prompt .= "3. 评分细则\n";
        $prompt .= "4. 反馈方式\n";
        
        return $prompt;
    }

    protected function parseAISuggestions(array $response): array
    {
        $suggestions = [];
        
        if (isset($response['suggestions'])) {
            $suggestions = $response['suggestions'];
        } else {
            $content = $response['content'] ?? '';
            $suggestions = $this->parseTextSuggestions($content);
        }
        
        return $suggestions;
    }

    protected function parseTextSuggestions(string $content): array
    {
        $suggestions = [
            'background' => [],
            'objectives' => [],
            'tasks' => [],
            'resources' => [],
            'assessment' => []
        ];
        
        $lines = explode("\n", $content);
        $currentSection = null;
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            if (empty($line)) continue;
            
            if (strpos($line, '项目背景') !== false) {
                $currentSection = 'background';
            } elseif (strpos($line, '项目目标') !== false) {
                $currentSection = 'objectives';
            } elseif (strpos($line, '项目任务') !== false) {
                $currentSection = 'tasks';
            } elseif (strpos($line, '所需资源') !== false) {
                $currentSection = 'resources';
            } elseif (strpos($line, '评估方式') !== false) {
                $currentSection = 'assessment';
            }
            
            if ($currentSection && strpos($line, '-') === 0) {
                $suggestion = trim(substr($line, 1));
                if (!empty($suggestion)) {
                    $suggestions[$currentSection][] = $suggestion;
                }
            }
        }
        
        return $suggestions;
    }

    protected function parseTasks(array $response): array
    {
        $tasks = [];
        
        if (isset($response['tasks'])) {
            $tasks = $response['tasks'];
        } else {
            $content = $response['content'] ?? '';
            $tasks = $this->parseListItems($content);
        }
        
        return $tasks;
    }

    protected function parseResources(array $response): array
    {
        $resources = [];
        
        if (isset($response['resources'])) {
            $resources = $response['resources'];
        } else {
            $content = $response['content'] ?? '';
            $resources = $this->parseListItems($content);
        }
        
        return $resources;
    }

    protected function parseAssessment(array $response): array
    {
        $assessment = [];
        
        if (isset($response['assessment'])) {
            $assessment = $response['assessment'];
        } else {
            $content = $response['content'] ?? '';
            $assessment = $this->parseListItems($content);
        }
        
        return $assessment;
    }

    protected function parseListItems(string $content): array
    {
        $items = [];
        $lines = explode("\n", $content);
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (strpos($line, '-') === 0) {
                $item = trim(substr($line, 1));
                if (!empty($item)) {
                    $items[] = $item;
                }
            }
        }
        
        return $items;
    }
} 