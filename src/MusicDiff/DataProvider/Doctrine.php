<?php

namespace MusicDiff\DataProvider;

use AppBundle\Entity\Artist as DBArtist;
use AppBundle\Entity\Album as DBAlbum;
use AppBundle\Entity\ArtistFetch;
use Doctrine\Bundle\DoctrineBundle\Registry;
use MusicDiff\Collection\Collection;
use MusicDiff\Collection\CollectionInterface;
use MusicDiff\Entity\Album;
use MusicDiff\Entity\Artist;
use MusicDiff\Entity\EntityInterface;

class Doctrine extends AbstractProvider
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var int
     */
    private $expirationDate;

    /**
     * Doctrine constructor.
     * @param Registry $registry Doctrine.
     * @param int $expirationDate Seconds. How long record in DB stays actual.
     */
    public function __construct(Registry $registry, int $expirationDate = 72 * 60 * 60)
    {
        $this->registry = $registry;
        $this->expirationDate = $expirationDate;
    }

    /**
     * Make a collection albums for the artist.
     * @inheritdoc
     */
    public function findByArtist(string $artistName): ?CollectionInterface
    {
        $collection = new Collection();
        $em = $this->registry->getManager();
        $dbArtist = $em->getRepository('AppBundle:Artist')->findArtistByName($artistName);

        $getNextCollection = function () use ($artistName) {
            if ($this->getNext()) {
                $nextColl = $this->getNext()->findByArtist($artistName);
                if ($nextColl !== null) {
                    $this->saveCollectionToDB($nextColl);
                    return $nextColl;
                }
            }
            return null;
        };

        if ($dbArtist !== null) {
            $lastFetch = $em->getRepository('AppBundle:ArtistFetch')->findOneBy(
                ['artist' => $dbArtist->getId()],
                ['lastFetch' => 'DESC']
            );
            if ($lastFetch &&
                $lastFetch->getLastFetch()->modify("+ {$this->expirationDate} seconds") < new \DateTime()
            ) {
                $nextCollection = $getNextCollection();
                if ($nextCollection !== null) {
                    return $nextCollection;
                }
            }
        } else {
            return $getNextCollection();
        }

        $albums = $dbArtist->getAlbums();
        $beginDate = $dbArtist->getBeginDate();
        $endDate = $dbArtist->getEndDate();
        $country = $dbArtist->getCountry();

        $mdArtist = new Artist($dbArtist->getName());
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
    private function saveCollectionToDB(CollectionInterface $collection)
    {
        $em = $this->registry->getManager();
        $storage = $collection->getStorage();

        /** @var Artist $artist */
        foreach ($storage as $artist) {
            $dbArtist = $em->getRepository('AppBundle:Artist')->findArtistByName($artist->getName());

            $newDbArtist = $dbArtist === null ? new DBArtist($artist->getName()) : $dbArtist;

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
                $dbAlbum = $em->getRepository('AppBundle:Album')->findAlbumByName($artist->getName());

                $newDBAlbum = $dbAlbum === null ? new DBAlbum($album->getName(), $newDbArtist) : $dbAlbum;

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
            $lastFetch = new ArtistFetch($newDbArtist, new \DateTime('now'));
            $em->persist($lastFetch);
        }

        $em->flush();
    }
}
