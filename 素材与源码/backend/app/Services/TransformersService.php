<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TransformersService
{
    protected $modelPath;
    protected $tokenizerPath;
    protected $device;

    public function __construct()
    {
        $this->modelPath = config('ai.bert_model_path');
        $this->tokenizerPath = config('ai.bert_tokenizer_path');
        $this->device = config('ai.device', 'cpu');
    }

    /**
     * 加载BERT模型
     */
    protected function loadModel()
    {
        try {
            $modelFile = Storage::path('models/bert.pt');
            if (!file_exists($modelFile)) {
                throw new \Exception("BERT模型文件不存在: {$modelFile}");
            }

            // 使用Python脚本加载模型
            $command = sprintf(
                'python %s/load_bert.py --model_path %s --tokenizer_path %s --device %s',
                base_path('scripts'),
                $modelFile,
                $this->tokenizerPath,
                $this->device
            );

            $output = shell_exec($command);
            return json_decode($output, true);
        } catch (\Exception $e) {
            Log::error('加载BERT模型失败: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 编码上下文
     */
    public function encodeContext(array $context): array
    {
        try {
            $model = $this->loadModel();
            
            // 准备输入数据
            $inputData = [
                'context' => $context
            ];

            // 调用Python脚本进行编码
            $command = sprintf(
                'python %s/encode_context.py --input %s',
                base_path('scripts'),
                escapeshellarg(json_encode($inputData))
            );

            $output = shell_exec($command);
            $result = json_decode($output, true);

            if (!$result || !isset($result['embedding'])) {
                throw new \Exception('编码上下文失败');
            }

            return $result['embedding'];
        } catch (\Exception $e) {
            Log::error('编码上下文失败: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 编码问题
     */
    public function encodeQuestion(string $question): array
    {
        try {
            $model = $this->loadModel();
            
            // 准备输入数据
            $inputData = [
                'question' => $question
            ];

            // 调用Python脚本进行编码
            $command = sprintf(
                'python %s/encode_question.py --input %s',
                base_path('scripts'),
                escapeshellarg(json_encode($inputData))
            );

            $output = shell_exec($command);
            $result = json_decode($output, true);

            if (!$result || !isset($result['embedding'])) {
                throw new \Exception('编码问题失败');
            }

            return $result['embedding'];
        } catch (\Exception $e) {
            Log::error('编码问题失败: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 分析文本
     */
    public function analyzeText(string $text): array
    {
        try {
            $model = $this->loadModel();
            
            // 准备输入数据
            $inputData = [
                'text' => $text
            ];

            // 调用Python脚本进行分析
            $command = sprintf(
                'python %s/analyze_text.py --input %s',
                base_path('scripts'),
                escapeshellarg(json_encode($inputData))
            );

            $output = shell_exec($command);
            $result = json_decode($output, true);

            if (!$result || !isset($result['analysis'])) {
                throw new \Exception('分析文本失败');
            }

            return $result['analysis'];
        } catch (\Exception $e) {
            Log::error('分析文本失败: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 分析语法
     */
    public function analyzeGrammar(string $text): array
    {
        try {
            $model = $this->loadModel();
            
            // 准备输入数据
            $inputData = [
                'text' => $text
            ];

            // 调用Python脚本进行语法分析
            $command = sprintf(
                'python %s/analyze_grammar.py --input %s',
                base_path('scripts'),
                escapeshellarg(json_encode($inputData))
            );

            $output = shell_exec($command);
            $result = json_decode($output, true);

            if (!$result || !isset($result['corrections'])) {
                throw new \Exception('分析语法失败');
            }

            return $result['corrections'];
        } catch (\Exception $e) {
            Log::error('分析语法失败: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 生成摘要
     */
    public function summarize(string $text, int $maxLength): string
    {
        try {
            $model = $this->loadModel();
            
            // 准备输入数据
            $inputData = [
                'text' => $text,
                'max_length' => $maxLength
            ];

            // 调用Python脚本生成摘要
            $command = sprintf(
                'python %s/summarize.py --input %s',
                base_path('scripts'),
                escapeshellarg(json_encode($inputData))
            );

            $output = shell_exec($command);
            $result = json_decode($output, true);

            if (!$result || !isset($result['summary'])) {
                throw new \Exception('生成摘要失败');
            }

            return $result['summary'];
        } catch (\Exception $e) {
            Log::error('生成摘要失败: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 文本分类
     */
    public function classify(string $text, array $categories): array
    {
        try {
            $model = $this->loadModel();
            
            // 准备输入数据
            $inputData = [
                'text' => $text,
                'categories' => $categories
            ];

            // 调用Python脚本进行分类
            $command = sprintf(
                'python %s/classify.py --input %s',
                base_path('scripts'),
                escapeshellarg(json_encode($inputData))
            );

            $output = shell_exec($command);
            $result = json_decode($output, true);

            if (!$result || !isset($result['classification'])) {
                throw new \Exception('文本分类失败');
            }

            return $result['classification'];
        } catch (\Exception $e) {
            Log::error('文本分类失败: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 情感分析
     */
    public function analyzeSentiment(string $text): array
    {
        try {
            $model = $this->loadModel();
            
            // 准备输入数据
            $inputData = [
                'text' => $text
            ];

            // 调用Python脚本进行情感分析
            $command = sprintf(
                'python %s/analyze_sentiment.py --input %s',
                base_path('scripts'),
                escapeshellarg(json_encode($inputData))
            );

            $output = shell_exec($command);
            $result = json_decode($output, true);

            if (!$result || !isset($result['sentiment'])) {
                throw new \Exception('情感分析失败');
            }

            return $result['sentiment'];
        } catch (\Exception $e) {
            Log::error('情感分析失败: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 提取关键词
     */
    public function extractKeywords(string $text, int $topK): array
    {
        try {
            $model = $this->loadModel();
            
            // 准备输入数据
            $inputData = [
                'text' => $text,
                'top_k' => $topK
            ];

            // 调用Python脚本提取关键词
            $command = sprintf(
                'python %s/extract_keywords.py --input %s',
                base_path('scripts'),
                escapeshellarg(json_encode($inputData))
            );

            $output = shell_exec($command);
            $result = json_decode($output, true);

            if (!$result || !isset($result['keywords'])) {
                throw new \Exception('提取关键词失败');
            }

            return $result['keywords'];
        } catch (\Exception $e) {
            Log::error('提取关键词失败: ' . $e->getMessage());
            throw $e;
        }
    }
} 