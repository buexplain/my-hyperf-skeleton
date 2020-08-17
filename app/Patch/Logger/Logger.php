<?php

declare(strict_types=1);

namespace App\Patch\Logger;

use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;
use Monolog\Logger as Base;

/**
 * Class Logger
 * @package App\Logger
 */
class Logger
{
    /**
     * Detailed debug information
     */
    const DEBUG = Base::DEBUG;

    /**
     * Interesting events
     *
     * Examples: User logs in, SQL logs.
     */
    const INFO = Base::INFO;

    /**
     * Uncommon events
     */
    const NOTICE = Base::NOTICE;

    /**
     * Exceptional occurrences that are not errors
     *
     * Examples: Use of deprecated APIs, poor use of an API,
     * undesirable things that are not necessarily wrong.
     */
    const WARNING = Base::WARNING;

    /**
     * Runtime errors
     */
    const ERROR = Base::ERROR;

    /**
     * Critical conditions
     *
     * Example: Application component unavailable, unexpected exception.
     */
    const CRITICAL = Base::CRITICAL;

    /**
     * Action must be taken immediately
     *
     * Example: Entire website down, database unavailable, etc.
     * This should trigger the SMS alerts and wake you up.
     */
    const ALERT = Base::ALERT;

    /**
     * Urgent alert.
     */
    const EMERGENCY = Base::EMERGENCY;

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public static function getInstance()
    {
        $container = ApplicationContext::getContainer();
        $logger = $container->get(LoggerFactory::class)->get();
        return $logger;
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public static function emergency($message, array $context = array())
    {
        self::getInstance()->emergency($message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public static function alert($message, array $context = array())
    {
        self::getInstance()->alert($message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public static function critical($message, array $context = array())
    {
        self::getInstance()->critical($message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public static function error($message, array $context = array())
    {
        self::getInstance()->error($message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public static function warning($message, array $context = array())
    {
        self::getInstance()->warning($message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public static function notice($message, array $context = array())
    {
        self::getInstance()->notice($message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public static function info($message, array $context = array())
    {
        self::getInstance()->info($message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public static function debug($message, array $context = array())
    {
        self::getInstance()->debug($message, $context);
    }
}