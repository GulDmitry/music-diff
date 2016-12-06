<?php

namespace MusicDiff\Collection;

use MusicDiff\Entity\Album;
use MusicDiff\Entity\Artist;

interface CollectionInterface
{
    /**
     * Add an artist to collection.
     * @param Artist $artist
     */
    public function addArtist(Artist $artist);

    /**
     * Add an album to collection.
     * The `UnknownArtist` should be used for grouping albums.
     * @param Artist $artist
     * @param Album $album
     */
    public function addAlbum(Artist $artist, Album $album);

    /**
     * Merge two collections.
     * @param CollectionInterface $collection
     * @return CollectionInterface Merged collection.
     */
    public function merge(CollectionInterface $collection): CollectionInterface;

    /**
     * Get the collection as ObjectStorage tree:
     * SplObjectStorage[
     *     Artist => SplObjectStorage[
     *         Album,
     *         Album,
     *     ],
     * ]
     * @return \SplObjectStorage
     */
    public function getStorage(): \SplObjectStorage;
}
