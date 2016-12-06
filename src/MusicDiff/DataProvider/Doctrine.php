<?php

namespace MusicDiff\DataProvider;

use Doctrine\Bundle\DoctrineBundle\Registry;
use MusicDiff\Collection\Collection;
use MusicDiff\Collection\CollectionInterface;
use MusicDiff\Entity\Album;
use MusicDiff\Entity\Artist;
use MusicDiff\Entity\EntityInterface;
use MusicDiff\Exception\NotFoundException;

class Doctrine implements DataProviderInterface
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param Registry $registry Doctrine.
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Make a collection albums for the artist.
     * @inheritdoc
     */
    public function findByArtist(string $artist): CollectionInterface
    {
        $collection = new Collection();
        $artist = $this->registry->getManager()->getRepository('AppBundle:Artist')->findArtistByName($artist);

        if ($artist === null) {
            throw new NotFoundException("Artist `{$artist}` is not found");
        }
        $albums = $artist->getAlbums();
        $beginDate = $artist->getBeginDate();
        $endDate = $artist->getEndDate();
        $country = $artist->getCountry();

        $mdArtist = new Artist($artist->getName());
        if ($beginDate !== null) {
            $mdArtist->setBeginDate($beginDate->format(EntityInterface::DATA_FORMAT));
        }
        if ($endDate !== null) {
            $mdArtist->setEndDate($endDate->format(EntityInterface::DATA_FORMAT));
        }
        if ($country) {
            $mdArtist->setCountry($country);
        }
        $collection->addArtist($mdArtist);

        /** @var \AppBundle\Entity\Album $release */
        foreach ($albums as $release) {
            $length = $release->getLength();
            $releaseDate = $release->getDate();
            $types = $release->getTypes();

            $mdAlbum = new Album($release->getName());
            if ($length !== null) {
                $mdAlbum->setLength($length);
            }
            if ($releaseDate !== null) {
                $mdAlbum->setReleaseDate($releaseDate->format(EntityInterface::DATA_FORMAT));
            }
            if ($types !== null) {
                $mdAlbum->setTypes($types);
            }
            $collection->addAlbum($mdArtist, $mdAlbum);
        }

        return $collection;
    }
}
