# 项目式备课平台

基于AI的项目式备课平台，专注于中小学科学、信息技术和通用课程的教学准备。

## 项目特点

- 项目情境生成器
- 四阶段活动设计器
- AI辅助备课
- 教学资源管理
- 智能问答系统

## 技术栈

### 后端
- PHP 8.1+
- Laravel 9.x
- MySQL 8.0+
- Redis 6.0+

### 前端
- Vue.js 3.x
- Element Plus
- Vite
- TypeScript

### AI服务
- PyTorch 1.12+
- Transformers 4.20+
- BERT/GPT模型

## 环境要求

### 服务器要求
- CPU: 4核以上
- 内存: 8GB以上
- 存储: 50GB以上
- GPU: NVIDIA GPU (推荐用于AI服务)

### 操作系统
- Ubuntu 20.04 LTS 或更高版本
- CentOS 8 或更高版本
- Windows Server 2019 或更高版本

## 安装部署

### 1. 环境配置

```bash
# 安装PHP依赖
sudo apt-get update
sudo apt-get install php8.1 php8.1-fpm php8.1-mysql php8.1-redis php8.1-curl php8.1-mbstring php8.1-xml php8.1-zip

# 安装Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# 安装Node.js和npm
curl -fsSL https://deb.nodesource.com/setup_16.x | sudo -E bash -
sudo apt-get install -y nodejs

# 安装Python依赖
sudo apt-get install python3 python3-pip python3-venv
pip3 install torch transformers numpy scikit-learn
```

### 2. 项目部署

```bash
# 克隆项目
git clone [项目地址]
cd project-based-lesson-preparation

# 安装后端依赖
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed

# 安装前端依赖
cd ../frontend
npm install
npm run build

# 配置AI模型
cd ../backend
mkdir -p storage/app/models
# 将BERT和GPT模型文件放入storage/app/models目录
```

### 3. 配置说明

#### 后端配置 (.env)
```env
APP_NAME=ProjectBasedLessonPreparation
APP_ENV=production
APP_DEBUG=false
APP_URL=http://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lesson_preparation
DB_USERNAME=your_username
DB_PASSWORD=your_password

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

AI_MODEL_PATH=storage/app/models
AI_DEVICE=cuda
```

#### AI服务配置
- BERT模型路径: `storage/app/models/bert.pt`
- GPT模型路径: `storage/app/models/gpt.pt`
- 模型配置文件: `config/ai.php`

## 项目结构

```
project-based-lesson-preparation/
├── backend/                # 后端代码
│   ├── app/               # 应用核心代码
│   ├── config/            # 配置文件
│   ├── database/          # 数据库迁移和种子
│   ├── routes/            # 路由定义
│   ├── storage/           # 存储目录
│   └── tests/             # 测试文件
├── frontend/              # 前端代码
│   ├── src/              # 源代码
│   ├── public/           # 公共资源
│   └── dist/             # 构建输出
└── scripts/              # Python脚本
    ├── generate_*.py     # 生成脚本
    └── load_*.py         # 模型加载脚本
```

## 主要功能

### 1. 项目情境生成
- 基于学科和年级生成项目情境
- 支持自定义情境参数
- 生成情境评估报告

### 2. 四阶段活动设计
- 准备阶段活动设计
- 实施阶段活动设计
- 评估阶段活动设计
- 反思阶段活动设计

### 3. AI辅助备课
- 教案自动生成
- 课件智能生成
- 教学资源推荐
- 智能问答系统

## 开发指南

### 代码规范
- 遵循PSR-12编码规范
- 使用PHPStan进行静态分析
- 使用PHPUnit进行单元测试

### 提交规范
- feat: 新功能
- fix: 修复bug
- docs: 文档更新
- style: 代码格式调整
- refactor: 代码重构
- test: 测试相关
- chore: 构建过程或辅助工具的变动

## 维护与支持

### 日志管理
- 错误日志: `storage/logs/laravel.log`
- AI服务日志: `storage/logs/ai.log`

### 监控指标
- 系统性能监控
- AI服务响应时间
- 用户行为分析

### 备份策略
- 数据库每日备份
- 模型文件定期备份
- 用户数据实时备份

## 许可证

本项目采用 MIT 许可证。详见 [LICENSE](LICENSE) 文件。

## 联系方式

- 项目负责人: [姓名]
- 邮箱: [邮箱地址]
- 技术支持: [联系方式] 