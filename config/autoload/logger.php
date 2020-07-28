<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

$appEnv = env('APP_ENV', 'prod');
if($appEnv == 'dev') { //开发环境
    $level = \Monolog\Logger::DEBUG;
}else if ($appEnv == 'test') { //测试环境
    $level = \Monolog\Logger::DEBUG;
}else if ($appEnv == 'pre') { //预生产环境
    $level = \Monolog\Logger::ERROR;
}else{ //生产环境
    $level = \Monolog\Logger::ERROR;
}

return [
    //默认日志
    'default' => [
        'handlers' => [
            //文件处理器
            [
                'class' => \Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf.log',
                    'level' => $level,
                ],
                'formatter' => [
                    'class' => \Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format' => null,
                        'dateFormat' => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
            //告警处理器
            [
                'class' => \Alarm\Handler::class,
                'constructor' => [
                    'alarm'=>function() {
                        return make(\Alarm\Contract\AlarmInterface::class, [
                            \Psr\Container\ContainerInterface::class => \Hyperf\Utils\ApplicationContext::getContainer()
                        ]);
                    },
                    //此处的handler对应的正是config/autoload/alarm.php配置的key值
                    'handlers'=>[
                        'dingTalk',
                    ],
                    //接收的日志级别
                    'level'=>\Monolog\Logger::ERROR,
                ],
            ],
        ],
    ],
    //sql记录
    'sql' => [
        'handlers'=>[
            [
                'class' => \Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/sql.log',
                    'level' => $level,
                ],
                'formatter' => [
                    'class' => \Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format' => null,
                        'dateFormat' => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
        ],
    ],
];
