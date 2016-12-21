<?php

namespace AppBundle\Aspect;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\After;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;

/**
 * Application logging aspect.
 */
class LoggingAspect implements Aspect
{
    use MusicDiffPointcutTrait;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Log all MusicDiff data provider find methods.
     * @param MethodInvocation $invocation
     * @After("$this->musicDiffDataProviderFind")
     */
    public function musicDiffFind(MethodInvocation $invocation): void
    {
        // In order to get returned data use Around with `$res = $invocation->proceed()`.
        $this->logger->debug("MusicDiff data provider {$invocation}", $invocation->getArguments());
    }
}
