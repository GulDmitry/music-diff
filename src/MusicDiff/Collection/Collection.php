<?php

namespace MusicDiff\Collection;

use MusicDiff\Entity\Album;
use MusicDiff\Entity\Artist;
use Symfony\Component\Asset\Packages;

class Collection implements CollectionInterface
{
    /**
     * @var \SplObjectStorage
     */
    private $storage;

    public function __construct()
    {
        $this->storage = new \SplObjectStorage();
    }

    /**
     * @inheritdoc
     */
    public function addArtist(Artist $artist)
    {
        $this->storage->attach($artist, new \SplObjectStorage());
    }

    /**
     * @inheritdoc
     */
    public function addAlbum(Artist $artist, Album $album)
    {
        if (!$this->storage->contains($artist)) {
            $this->storage->attach($artist, new \SplObjectStorage());
        }
        $this->storage[$artist]->attach($album);
    }

    /**
     * Merge two collections. Passed data replaces the original.
     * @inheritdoc
     */
    public function merge(CollectionInterface $collection): CollectionInterface
    {
        $resultColl = new self();
        $storage = $this->storage;
        $storageToMerge = $collection->getStorage();

        if (!$this->storage->count()) {
            return $collection;
        } elseif (!$storageToMerge->count()) {
            return $this;
        }

        $resultCollection = [];

        /**
         * Save links to artist and its albums.
         * @param Artist $artist
         * @param \SplObjectStorage $storage
         */
        $addArtistToCollection = function (Artist $artist, \SplObjectStorage $storage) use (&$resultCollection) {
            $artistName = strtolower($artist->getName());

            if (!isset($resultCollection[$artistName]['artist'])) {
                $resultCollection[$artistName]['artist'] = $artist;
            }
            if (!isset($resultCollection[$artistName]['albums'])) {
                $resultCollection[$artistName]['albums'] = [];
            }

            foreach ($storage[$artist] as $album) {
                $albumName = strtolower($album->getName());

                if (!isset($resultCollection[$artistName]['albums'][$albumName])) {
                    $resultCollection[$artistName]['albums'][$albumName] = $album;
                }
            }
        };

        /**
         * @var Artist $origArtist
         * @var Artist $artist
         * @var Album $origAlbum
         * @var Album $album
         */
        foreach ($storage as $origArtist) {
            $origArtistName = strtolower($origArtist->getName());

            $addArtistToCollection($origArtist, $storage);

            foreach ($storageToMerge as $artist) {
                $matchArtist = strtolower($artist->getName()) === $origArtistName;

                if ($matchArtist) {
                    $newArtist = $this->mergeArtists($origArtist, $artist);

                    $resultCollection[strtolower($newArtist->getName())]['artist'] = $newArtist;

                    // Merge Albums.
                    $albumRef = &$resultCollection[strtolower($newArtist->getName())];

                    // Just copy albums if original artist does not have any.
                    if ($this->storage[$origArtist]->count() === 0 && $storageToMerge[$artist]->count() !== 0) {
                        foreach ($storageToMerge[$artist] as $album) {
                            $albumRef['albums'][strtolower($album->getName())] = $album;
                        }
                        continue;
                    }

                    foreach ($this->storage[$origArtist] as $origAlbum) {
                        $origAlbumName = strtolower($origAlbum->getName());

                        foreach ($storageToMerge[$artist] as $album) {
                            $matchAlbum = strtolower($album->getName()) === $origAlbumName;

                            if ($matchAlbum) {
                                $newAlbum = $this->mergeAlbums($origAlbum, $album);

                                $albumRef['albums'][strtolower($newAlbum->getName())] = $newAlbum;
                            } else {
                                if (!isset($albumRef['albums'][strtolower($album->getName())])) {
                                    $albumRef['albums'][strtolower($album->getName())] = $album;
                                }
                            }
                        }
                    }
                } else {
                    $addArtistToCollection($artist, $storageToMerge);
                }
            }
        }

        foreach ($resultCollection as $data) {
            $artist = $data['artist'];
            $resultColl->addArtist($artist);
            foreach ($data['albums'] as $album) {
                $resultColl->addAlbum($artist, $album);
            }
        }
        return $resultColl;
    }

    /**
     * @inheritdoc
     */
    public function getStorage(): \SplObjectStorage
    {
        return $this->storage;
    }

    /**
     * Populate the original artist with passed data.
     * @param Artist $origArtist
     * @param Artist $artist
     * @return Artist
     */
    private function mergeArtists(Artist $origArtist, Artist $artist): Artist
    {
        $resultArtist = new Artist($artist->getName());
        $map = [
            'getName' => 'setName',
            'getCountry' => 'setCountry',
            'getBeginDate' => 'setBeginDate',
            'getEndDate' => 'setEndDate',
            'getGenres' => 'setGenres',
        ];
        foreach ($map as $getter => $setter) {
            $val = $artist->$getter();
            if ($val) {
                $resultArtist->$setter($val);
            } elseif ($val = $origArtist->$getter()) {
                $resultArtist->$setter($val);
            }
        }
        return $resultArtist;
    }

    /**
     * Populate the original album with passed data.
     * @param Album $origAlbum
     * @param Album $album
     * @return Album
     */
    private function mergeAlbums(Album $origAlbum, Album $album): Album
    {
        $resultAlbum = new Album($album->getName());
        $map = [
            'getName' => 'setName',
            'getLength' => 'setLength',
            'getReleaseDate' => 'setReleaseDate',
        ];
        foreach ($map as $getter => $setter) {
            $val = $album->$getter();
            if ($val) {
                $resultAlbum->$setter($val);
            } elseif ($val = $origAlbum->$getter()) {
                $resultAlbum->$setter($val);
            }
        }
        return $resultAlbum;
    }

    /**
     * Make a copy of storage.
     */
    public function __clone()
    {
        $this->storage = clone $this->storage;
    }
}
