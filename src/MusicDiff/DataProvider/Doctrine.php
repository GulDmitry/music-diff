<?php

namespace MusicDiff\DataProvider;

use AppBundle\Entity\Artist as DBArtist;
use AppBundle\Entity\Album as DBAlbum;
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

    /**
     * Save the collection to DB.
     * @param CollectionInterface $collection
     */
    public function saveCollectionToDB(CollectionInterface $collection)
    {
        $em = $this->registry->getManager();
        $storage = $collection->getStorage();

        /** @var Artist $artist */
        foreach ($storage as $artist) {
            $dbArtist = $em->getRepository('AppBundle:Artist')->findArtistByName($artist->getName());
            if ($dbArtist !== null) {
                continue;
            }
            $newDbArtist = new DBArtist($artist->getName());
            if ($artist->getBeginDate() !== null) {
                $newDbArtist->setBeginDate(new \DateTime($artist->getBeginDate()));
            }
            if ($artist->getEndDate() !== null) {
                $newDbArtist->setEndDate(new \DateTime($artist->getEndDate()));
            }
            if ($artist->getCountry() !== null) {
                $newDbArtist->setCountry($artist->getCountry());
            }

            /** @var Album $album */
            foreach ($storage[$artist] as $album) {
                $newDBAlbum = new DBAlbum($album->getName(), $newDbArtist);
                if ($album->getLength() !== null) {
                    $newDBAlbum->setLength($album->getLength());
                }
                if ($album->getReleaseDate() !== null) {
                    $newDBAlbum->setDate(new \DateTime($album->getReleaseDate()));
                }
                if ($album->getTypes() !== null) {
                    $newDBAlbum->setTypes($album->getTypes());
                }
                $em->persist($newDBAlbum);
            }
            $em->persist($newDbArtist);
        }
        $em->flush();
    }
}
