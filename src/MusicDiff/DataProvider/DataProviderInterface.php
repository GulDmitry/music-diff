<?php

namespace MusicDiff\DataProvider;

use MusicDiff\Collection\CollectionInterface;

interface DataProviderInterface
{
    /**
     * Find artist and albums by artist name.
     * @param string $artist
     * @return CollectionInterface
     */
    public function findByArtist(string $artist): CollectionInterface;
}
