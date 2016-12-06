<?php
namespace MusicDiff\Collection\Converter;

use MusicDiff\Collection\Collection;
use MusicDiff\Collection\CollectionInterface;
use MusicDiff\Entity\Album;
use MusicDiff\Entity\Artist;

class ArrayConverter implements ConverterInterface
{
    /**
     * Represents the collection as array.
     * @param CollectionInterface $collection
     * @return array
     */
    public function fromCollection(CollectionInterface $collection)
    {
        $result = [];
        $storage = $collection->getStorage();

        /**
         * @var Artist $artist
         * @var Album $album
         */
        foreach ($storage as $artist) {
            $arrayArtist = [
                'name' => $artist->getName(),
                'country' => $artist->getCountry(),
                'beginDate' => $artist->getBeginDate(),
                'endDate' => $artist->getEndDate(),
                'genres' => $artist->getGenres(),
                'albums' => [],
            ];
            foreach ($storage[$artist] as $album) {
                $arrayArtist['albums'][] = [
                    'name' => $album->getName(),
                    'releaseDate' => $album->getReleaseDate(),
                    'length' => $album->getLength(),
                    'types' => $album->getTypes(),
                ];
            }
            $result[] = $arrayArtist;
        }

        return $result;
    }

    /**
     * Restore collection from array.
     * @param array $data
     * @inheritdoc
     */
    public function toCollection($data): CollectionInterface
    {
        $result = new Collection();

        foreach ($data as $artist) {
            $artistObj = new Artist($artist['name']);

            foreach ([
                         'country' => 'setCountry',
                         'beginDate' => 'setBeginDate',
                         'endDate' => 'setEndDate',
                         'genres' => 'setGenres',
                     ] as $prop => $setter) {
                if ($artist[$prop] !== null) {
                    $artistObj->$setter($artist[$prop]);
                }
            }

            $result->addArtist($artistObj);

            foreach ($artist['albums'] as $album) {
                $albumObj = new Album($album['name']);

                foreach ([
                             'length' => 'setLength',
                             'releaseDate' => 'setReleaseDate',
                             'types' => 'setTypes',
                         ] as $prop => $setter) {
                    if ($album[$prop] !== null) {
                        $albumObj->$setter($album[$prop]);
                    }
                }

                $result->addAlbum($artistObj, $albumObj);
            }
        }

        return $result;
    }
}
