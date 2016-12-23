<?php
namespace Tests\Unit\MusicDiff\Collection;

use MusicDiff\Collection\Collection;
use MusicDiff\Entity\Album;
use MusicDiff\Entity\Artist;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->collection = new Collection();
    }

    /**
     * Test that collection creates specific objects which can be received later.
     */
    public function testAlbumArtistAdding()
    {
        $artist = new Artist('artist');
        $artist->setCountry('DE');
        $this->collection->addArtist($artist);

        $album = new Album('album');
        $album->setLength(6);
        $this->collection->addAlbum($artist, $album);

        $storage = $this->collection->getStorage();

        $storage->rewind();
        /** @var Artist $actualArtist */
        $actualArtist = $storage->current();

        $actualAlbumStorage = $storage->getInfo();
        $actualAlbumStorage->rewind();
        /** @var Album $actualAlbum */
        $actualAlbum = $actualAlbumStorage->current();

        $this->assertInstanceOf(\SplObjectStorage::class, $storage);
        $this->assertEquals('artist', $actualArtist->getName());
        $this->assertEquals('DE', $actualArtist->getCountry());

        $this->assertInstanceOf(\SplObjectStorage::class, $actualAlbumStorage);
        $this->assertEquals('album', $actualAlbum->getName());
        $this->assertEquals(6, $actualAlbum->getLength());
    }

    /**
     * Merge collections. Passed collection replaces the original.
     */
    public function testMergeSubData()
    {
        $originalArtist = new Artist('Artist One');
        $originalArtist->setCountry('DE');
        $originalArtist->setBeginDate('2000-01-01');
        $this->collection->addArtist($originalArtist);

        $originalAlbum = new Album('Album One');
        $originalAlbum->setLength(6);
        $originalAlbum->setReleaseDate('2001-01-01');
        $this->collection->addAlbum($originalArtist, $originalAlbum);

        $newCollection = new Collection();
        // Replace the original.
        $newArtist1 = new Artist('artist one');
        $newArtist1->setCountry('FR');
        $newCollection->addArtist($newArtist1);
        // Add.
        $newArtist2 = new Artist('Artist Two');
        $newCollection->addArtist($newArtist2);
        // Replace the original.
        $newAlbum1 = new Album('album one');
        $newAlbum1->setLength(10);
        $newCollection->addAlbum($newArtist1, $newAlbum1);
        // Add.
        $newAlbum2 = new Album('Album Two');
        $newCollection->addAlbum($newArtist1, $newAlbum2);

        $expectedCollection = new Collection();

        $expectedArtist = new Artist('artist one');
        $expectedArtist->setCountry('FR');
        $expectedArtist->setBeginDate('2000-01-01');

        $expectedAlbum = new Album('album one');
        $expectedAlbum->setLength(10);
        $expectedAlbum->setReleaseDate('2001-01-01');

        $expectedCollection->addAlbum($expectedArtist, $expectedAlbum);
        $expectedCollection->addAlbum($expectedArtist, $newAlbum2);
        $expectedCollection->addArtist($newArtist2);

        $actualCollection = $this->collection->merge($newCollection);

        $this->assertEquals(
            $expectedCollection->getStorage()->serialize(),
            $actualCollection->getStorage()->serialize()
        );
    }

    /**
     * Merge should works event if collection is empty.
     */
    public function testMergeEmptyCollection()
    {
        $emptyCollection = new Collection();

        $collection = new Collection();
        $collection->addArtist(new Artist('artist'));

        $this->assertEquals($collection, $collection->merge($emptyCollection));
        $this->assertEquals($collection, $emptyCollection->merge($collection));
    }


    /**
     * Merge should works event if album collection is empty.
     */
    public function testMergeEmptyAlbums()
    {
        $artist = new Artist('artist');
        $album = new Album('album');

        $emptyCollection = new Collection();
        $emptyCollection->addArtist($artist);

        $collection = new Collection();
        $collection->addAlbum($artist, $album);

        $actualCollection1 = $emptyCollection->merge($collection);
        $actualCollection2 = $collection->merge($emptyCollection);

        $this->assertEquals(
            $collection->getStorage()->serialize(),
            $actualCollection1->getStorage()->serialize()
        );
        $this->assertEquals(
            $collection->getStorage()->serialize(),
            $actualCollection2->getStorage()->serialize()
        );
    }
}
