<?php

return [
    // 模型路径配置
    'model_path' => storage_path('app/models'),
    'bert_model_path' => storage_path('app/models/bert'),
    'bert_tokenizer_path' => storage_path('app/models/bert/tokenizer'),
    
    // 设备配置
    'device' => env('AI_DEVICE', 'cpu'),
    
    // 模型参数
    'max_length' => 512,
    'batch_size' => 32,
    'num_beams' => 4,
    'temperature' => 0.7,
    'top_k' => 50,
    'top_p' => 0.9,
    
    // 缓存配置
    'cache_dir' => storage_path('app/cache/ai'),
    
    // 超时配置
    'timeout' => 300,
    
    // 日志配置
    'log_level' => env('AI_LOG_LEVEL', 'info'),
    
    // API配置
    'api_key' => env('AI_API_KEY'),
    'api_endpoint' => env('AI_API_ENDPOINT'),
    
    // 模型版本
    'bert_version' => 'bert-base-chinese',
    'gpt_version' => 'gpt2-chinese',
]; 