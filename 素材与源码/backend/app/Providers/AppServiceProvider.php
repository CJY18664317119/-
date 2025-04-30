<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Services\AIServiceInterface;
use App\Services\Impl\AIServiceImpl;
use App\Contracts\Repositories\AIChatRepositoryInterface;
use App\Repositories\Impl\AIChatRepositoryImpl;
use App\Contracts\Repositories\ActivityDesignRepositoryInterface;
use App\Repositories\Impl\ActivityDesignRepositoryImpl;
use App\Contracts\Services\ActivityDesignServiceInterface;
use App\Services\Impl\ActivityDesignServiceImpl;
use App\Contracts\Repositories\ProjectScenarioRepositoryInterface;
use App\Repositories\Impl\ProjectScenarioRepositoryImpl;
use App\Contracts\Services\ProjectScenarioServiceInterface;
use App\Services\Impl\ProjectScenarioServiceImpl;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // AI 服务绑定
        $this->app->bind(AIServiceInterface::class, AIServiceImpl::class);
        $this->app->bind(AIChatRepositoryInterface::class, AIChatRepositoryImpl::class);

        // 活动设计服务绑定
        $this->app->bind(ActivityDesignRepositoryInterface::class, ActivityDesignRepositoryImpl::class);
        $this->app->bind(ActivityDesignServiceInterface::class, ActivityDesignServiceImpl::class);

        // 项目情境服务绑定
        $this->app->bind(ProjectScenarioRepositoryInterface::class, ProjectScenarioRepositoryImpl::class);
        $this->app->bind(ProjectScenarioServiceInterface::class, ProjectScenarioServiceImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
} 