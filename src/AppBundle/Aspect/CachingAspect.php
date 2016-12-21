<?php

namespace AppBundle\Aspect;

use Doctrine\Common\Cache\Cache;
use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Around;
use MusicDiff\Collection\CollectionInterface;
use Psr\Log\LoggerInterface;

class CachingAspect implements Aspect
{
    use MusicDiffPointcutTrait;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Cache
     */
    private $cache;

    const LIFETIME = 1 * 60 * 60;

    /**
     * @param Cache $cache
     * @param LoggerInterface $logger
     */
    public function __construct(Cache $cache, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->cache = $cache;
    }

    /**
     * Cache find method results.
     * @param MethodInvocation $invocation Invocation
     * @return CollectionInterface
     * @Around("$this->musicDiffDataProviderFind")
     */
    public function musicDiffFind(MethodInvocation $invocation): ?CollectionInterface
    {
        $argsKey = array_reduce($invocation->getArguments(), function ($carry = '', $val) {
            return $carry .= strtolower($val);
        });
        $context = $invocation->getThis();
        $class = is_object($context) ? get_class($context) : $context;
        $key = "{$class}:{$invocation->getMethod()->name}:{$argsKey}";

        if ($this->cache->contains($key)) {
            $this->logger->debug("Take from cache {$invocation} key {$key}");
            return $this->cache->fetch($key);
        }

        $data = $invocation->proceed();
        $this->cache->save($key, $data, self::LIFETIME);

        return $data;
    }
}
