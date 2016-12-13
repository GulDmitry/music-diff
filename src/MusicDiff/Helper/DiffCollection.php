<?php

namespace MusicDiff\Helper;

use MusicDiff\Collection\CollectionInterface;
use MusicDiff\Collection\Converter\ArrayConverter;

class DiffCollection
{
    /**
     * Leave elements that are not present in the first collection.
     * Artists are taken from the first collection only.
     * @param CollectionInterface $coll1
     * @param CollectionInterface $coll2
     * @return CollectionInterface
     */
    public function calculateDiff(CollectionInterface $coll1, CollectionInterface $coll2): CollectionInterface
    {
        $arrayConverter = new ArrayConverter();

        $coll1Array = $resultArray = $arrayConverter->fromCollection($coll1);
        $coll2Array = $arrayConverter->fromCollection($coll2);

        foreach ($coll1Array as $origArtistKey => $origArtist) {
            $resultArray[$origArtistKey]['albums'] = [];

            foreach ($coll2Array as $artist) {
                if (strtolower($origArtist['name']) !== strtolower($artist['name'])) {
                    continue;
                }

                // Albums.
                $albumNames = array_map(function ($val) {
                    return strtolower($val['name']);
                }, $origArtist['albums']);

                foreach ($artist['albums'] as $album) {
                    if (!in_array(strtolower($album['name']), $albumNames)) {
                        $resultArray[$origArtistKey]['albums'][] = $album;
                    }
                }

            }
        }
        return $arrayConverter->toCollection($resultArray);
    }
}
