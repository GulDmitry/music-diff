<?php

namespace AppBundle\Aspect;

use Go\Lang\Annotation\Pointcut;

trait MusicDiffPointcutTrait
{
    /**
     * Pointcut for MusicDiff data provider.
     * @Pointcut("execution(public MusicDiff\DataProvider\*->find*(*))")
     */
    protected function musicDiffDataProviderFind(): void
    {
    }
}
