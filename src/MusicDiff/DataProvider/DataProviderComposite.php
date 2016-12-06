<?php

namespace MusicDiff\DataProvider;

use MusicDiff\Collection\Collection;
use MusicDiff\Collection\CollectionInterface;
use MusicDiff\Exception\InvalidArgumentException;
use MusicDiff\Exception\NotFoundException;

class DataProviderComposite implements DataProviderInterface
{
    /**
     * @var array
     */
    private $dataProviders = [];

    /**
     * @var bool Flag to search in all providers.
     */
    private $merge = false;

    /**
     * @param bool $merge Go through all provider and collect data.
     * @param DataProviderInterface[] ...$dataProviders
     */
    public function __construct(bool $merge, DataProviderInterface ...$dataProviders)
    {
        if (empty($dataProviders)) {
            throw new InvalidArgumentException('At least one data provider should be specified.');
        }
        $this->dataProviders = $dataProviders;
        $this->merge = $merge;
    }

    /**
     * A composite method.
     * @inheritdoc
     */
    public function findByArtist(string $artist): CollectionInterface
    {
        $resultCollection = new Collection();
        foreach ($this->dataProviders as $provider) {
            try {
                $collection = $provider->findByArtist($artist);
            } catch (NotFoundException $ex) {
                continue;
            }
            if ($this->merge) {
                $resultCollection = $resultCollection->merge($collection);
            } else {
                $resultCollection = $collection;
                break;
            }
        }
        if ($resultCollection->getStorage()->count() === 0) {
            throw new NotFoundException("Artist `{$artist}` is not found.");
        }
        return $resultCollection;
    }
}
