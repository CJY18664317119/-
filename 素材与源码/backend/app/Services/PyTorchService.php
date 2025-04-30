<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PyTorchService
{
    protected $modelPath;
    protected $tokenizerPath;
    protected $device;

    public function __construct()
    {
        $this->modelPath = config('ai.model_path');
        $this->tokenizerPath = config('ai.tokenizer_path');
        $this->device = config('ai.device', 'cpu');
    }

    /**
     * 加载模型
     */
    protected function loadModel(string $modelType)
    {
        try {
            $modelFile = Storage::path("models/{$modelType}.pt");
            if (!file_exists($modelFile)) {
                throw new \Exception("模型文件不存在: {$modelFile}");
            }

            // 使用Python脚本加载模型
            $command = sprintf(
                'python %s/load_gpt.py --model_path %s --device %s',
                base_path('scripts'),
                $modelFile,
                $this->device
            );

            $output = shell_exec($command);
            return json_decode($output, true);
        } catch (\Exception $e) {
            Log::error('加载模型失败: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 生成项目情境
     */
    public function generateScenario(array $contextEmbedding): array
    {
        try {
            $model = $this->loadModel('gpt');
            
            // 准备输入数据
            $inputData = [
                'context' => $contextEmbedding,
                'type' => 'scenario'
            ];

            // 调用Python脚本生成情境
            $command = sprintf(
                'python %s/generate_scenario.py --input %s',
                base_path('scripts'),
                escapeshellarg(json_encode($inputData))
            );

            $output = shell_exec($command);
            $result = json_decode($output, true);

            if (!$result || !isset($result['scenario'])) {
                throw new \Exception('生成项目情境失败');
            }

            return $result['scenario'];
        } catch (\Exception $e) {
            Log::error('生成项目情境失败: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 生成活动设计
     */
    public function generateActivities(array $scenarioEmbedding): array
    {
        try {
            $model = $this->loadModel('gpt');
            
            // 准备输入数据
            $inputData = [
                'scenario' => $scenarioEmbedding,
                'type' => 'activities'
            ];

            // 调用Python脚本生成活动
            $command = sprintf(
                'python %s/generate_activities.py --input %s',
                base_path('scripts'),
                escapeshellarg(json_encode($inputData))
            );

            $output = shell_exec($command);
            $result = json_decode($output, true);

            if (!$result || !isset($result['activities'])) {
                throw new \Exception('生成活动设计失败');
            }

            return $result['activities'];
        } catch (\Exception $e) {
            Log::error('生成活动设计失败: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 生成教案
     */
    public function generateLessonPlan(array $data): array
    {
        try {
            $model = $this->loadModel('gpt');
            
            // 准备输入数据
            $inputData = [
                'data' => $data,
                'type' => 'lesson_plan'
            ];

            // 调用Python脚本生成教案
            $command = sprintf(
                'python %s/generate_lesson_plan.py --input %s',
                base_path('scripts'),
                escapeshellarg(json_encode($inputData))
            );

            $output = shell_exec($command);
            $result = json_decode($output, true);

            if (!$result || !isset($result['lesson_plan'])) {
                throw new \Exception('生成教案失败');
            }

            return $result['lesson_plan'];
        } catch (\Exception $e) {
            Log::error('生成教案失败: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 生成课件
     */
    public function generateCourseware(array $data): array
    {
        try {
            $model = $this->loadModel('gpt');
            
            // 准备输入数据
            $inputData = [
                'data' => $data,
                'type' => 'courseware'
            ];

            // 调用Python脚本生成课件
            $command = sprintf(
                'python %s/generate_courseware.py --input %s',
                base_path('scripts'),
                escapeshellarg(json_encode($inputData))
            );

            $output = shell_exec($command);
            $result = json_decode($output, true);

            if (!$result || !isset($result['courseware'])) {
                throw new \Exception('生成课件失败');
            }

            return $result['courseware'];
        } catch (\Exception $e) {
            Log::error('生成课件失败: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 生成教学资源
     */
    public function generateResources(array $contextEmbedding): array
    {
        try {
            $model = $this->loadModel('gpt');
            
            // 准备输入数据
            $inputData = [
                'context' => $contextEmbedding,
                'type' => 'resources'
            ];

            // 调用Python脚本生成资源
            $command = sprintf(
                'python %s/generate_resources.py --input %s',
                base_path('scripts'),
                escapeshellarg(json_encode($inputData))
            );

            $output = shell_exec($command);
            $result = json_decode($output, true);

            if (!$result || !isset($result['resources'])) {
                throw new \Exception('生成教学资源失败');
            }

            return $result['resources'];
        } catch (\Exception $e) {
            Log::error('生成教学资源失败: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 生成回答
     */
    public function generateAnswer(array $questionEmbedding, array $contextEmbedding): string
    {
        try {
            $model = $this->loadModel('gpt');
            
            // 准备输入数据
            $inputData = [
                'question' => $questionEmbedding,
                'context' => $contextEmbedding
            ];

            // 调用Python脚本生成回答
            $command = sprintf(
                'python %s/generate_answer.py --input %s',
                base_path('scripts'),
                escapeshellarg(json_encode($inputData))
            );

            $output = shell_exec($command);
            $result = json_decode($output, true);

            if (!$result || !isset($result['answer'])) {
                throw new \Exception('生成回答失败');
            }

            return $result['answer'];
        } catch (\Exception $e) {
            Log::error('生成回答失败: ' . $e->getMessage());
            throw $e;
        }
    }
} 