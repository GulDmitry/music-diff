<?php

namespace MusicDiff\DataProvider;

use MusicDiff\Collection\CollectionInterface;

abstract class AbstractProvider implements DataProviderInterface
{
    /**
     * @var AbstractProvider
     */
    protected $next;

    /**
     * @inheritdoc
     */
    abstract public function findByArtist(string $artistName): ?CollectionInterface;

    /**
     * Set next provider in the chain.
     * @param DataProviderInterface $dataProvider
     */
    public function setNext(DataProviderInterface $dataProvider)
    {
        $this->next = $dataProvider;
    }

    /**
     * Get next data provider.
     * @return DataProviderInterface|null
     */
    public function getNext(): ?DataProviderInterface
    {
        return $this->next;
    }
}
