<?php

namespace MusicDiff\DataProvider;

use MusicDiff\Collection\CollectionInterface;

interface DataProviderInterface
{
    /**
     * Find artist and albums by artist name.
     * @param string $artistName
     * @return CollectionInterface|null
     */
    public function findByArtist(string $artistName): ?CollectionInterface;
}
