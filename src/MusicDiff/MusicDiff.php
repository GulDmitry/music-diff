<?php

namespace MusicDiff;

use MusicDiff\Collection\CollectionInterface;
use MusicDiff\DataProvider\DataProviderInterface;
use MusicDiff\Entity\Artist;
use MusicDiff\Exception\InvalidArgumentException;
use MusicDiff\Exception\RuntimeException;

class MusicDiff
{
    /**
     * @var CollectionInterface
     */
    private $initCollection;

    /**
     * @var DataProviderInterface
     */
    private $dataProvider;

    /**
     * @param DataProviderInterface $dataProvider
     */
    public function __construct(DataProviderInterface $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    /**
     * Set init collection.
     * @param CollectionInterface $collection
     */
    public function setInitCollection(CollectionInterface $collection): void
    {
        $this->initCollection = $collection;
    }

    /**
     * Restore the init collection by filling its missed parts.
     * @return CollectionInterface|null
     */
    public function restoreCollection(): CollectionInterface
    {
        if ($this->initCollection === null) {
            throw new InvalidArgumentException('Initial collection should not be empty.');
        }
        $restoredCollection = clone $this->initCollection;
        $storage = $this->initCollection->getStorage();

        /** @var Artist $artist */
        foreach ($storage as $artist) {
            $collection = $this->dataProvider->findByArtist($artist->getName());
            if ($collection === null) {
                continue;
            }
            $restoredCollection = $restoredCollection->merge($collection);
        }

        if ($restoredCollection === null) {
            throw new RuntimeException('Could not restore the collection');
        }
        return $restoredCollection;
    }
}
