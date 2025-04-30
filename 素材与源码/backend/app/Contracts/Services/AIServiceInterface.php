<?php

namespace App\Contracts\Services;

interface AIServiceInterface
{
    /**
     * 生成项目情境
     */
    public function generateScenario(array $context): array;

    /**
     * 生成活动设计
     */
    public function generateActivities(array $scenario): array;

    /**
     * 生成教案
     */
    public function generateLessonPlan(array $data): array;

    /**
     * 生成课件
     */
    public function generateCourseware(array $data): array;

    /**
     * 生成教学资源
     */
    public function generateTeachingResources(array $requirements): array;

    /**
     * 智能问答（仅限教学相关）
     */
    public function answerQuestion(string $question, array $context): array;
} 