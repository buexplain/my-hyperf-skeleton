<?php

declare(strict_types=1);

return [
    //钉钉机器人配置
    'dingTalk' => [
        'class' => \Alarm\Handler\DingTalk\DingTalk::class,
        'constructor' => [
            'formatter' => [
                'class' => \Alarm\Handler\DingTalk\TextFormatter::class,
                'constructor' => [],
            ],
            'robots' => [
                ['url' => env('DING_TALK_ROBOT', ''), 'secret' => env('DING_TALK_SECRET', '')],
            ]
        ],
    ],
];