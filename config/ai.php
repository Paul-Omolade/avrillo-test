<?php

return [
    'provider' => env('AI_PROVIDER', 'openai'),

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'model' => env('OPENAI_MODEL', 'gpt-5.4'),
        'url' => env('OPENAI_URL', 'https://api.openai.com/v1/chat/completions'),
    ],

    'claude' => [
        'api_key' => env('CLAUDE_API_KEY'),
        'model' => env('CLAUDE_MODEL', 'claude-3-5-sonnet-latest'),
        'url' => env('CLAUDE_URL', 'https://api.anthropic.com/v1/messages'),
        'version' => env('CLAUDE_VERSION', '2023-06-01'),
    ],

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-1.5-pro'),
        'url' => env('GEMINI_URL', 'https://generativelanguage.googleapis.com/v1beta/models'),
    ],
];
