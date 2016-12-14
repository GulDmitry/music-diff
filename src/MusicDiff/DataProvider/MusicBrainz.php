<?php

namespace MusicDiff\DataProvider;

use MusicBrainz\Artist as MusicBrainzArtist;
use MusicBrainz\Filters\ArtistFilter;
use MusicBrainz\MusicBrainz as MusicBrainzClient;
use MusicDiff\Entity\Album;
use MusicDiff\Entity\Artist;
use MusicDiff\Collection\Collection;
use MusicDiff\Collection\CollectionInterface;

class MusicBrainz implements DataProviderInterface
{
    /**
     * @var MusicBrainzClient
     */
    private $client;

    /**
     * Set the user agent for POST requests (and GET requests for user tags).
     * @param MusicBrainzClient $musicBrainzClient
     */
    public function __construct(MusicBrainzClient $musicBrainzClient)
    {
        $this->client = $musicBrainzClient;
    }

    /**
     * Make a collection albums for the artist.
     * @inheritdoc
     */
    public function findByArtist(string $artistName): ?CollectionInterface
    {
        $collection = new Collection();
        $artists = $this->client->search(new ArtistFilter(['artist' => $artistName]), 1);

        /** @var MusicBrainzArtist $current */
        $current = $artists[0];

        if (!$current || $current->getScore() < 95) {
            return null;
        }
        /**
         * @var string $country
         * @var string $beginDate
         * @var string $endDate
         */
        foreach (['country', 'beginDate', 'endDate'] as $prop) {
            $reflection = new \ReflectionClass($current);
            $property = $reflection->getProperty($prop);
            $property->setAccessible(true);
            $$prop = $property->getValue($current);
        }
        $releases = $this->client->browseReleaseGroup('artist', $current->getId(), 100, null, []);

        $artist = new Artist($current->getName());
        if ($beginDate !== null) {
            $artist->setBeginDate($beginDate);
        }
        if ($endDate !== null) {
            $artist->setEndDate($endDate);
        }
        if ($country) {
            $artist->setCountry($country);
        }
        $collection->addArtist($artist);

        foreach ($releases['release-groups'] as $release) {
            $album = new Album($release['title']);
            $album->setTypes(array_merge([$release['primary-type']], $release['secondary-types']));
            if ($release['first-release-date']) {
                $album->setReleaseDate($release['first-release-date']);
            }
            $collection->addAlbum($artist, $album);
        }

        return $collection;
    }
}
