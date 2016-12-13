<?php
namespace Tests\Unit\MusicDiff\Collection;

use MusicDiff\Collection\Collection;
use MusicDiff\Entity\Album;
use MusicDiff\Entity\Artist;
use MusicDiff\Helper\DiffCollection;

class DiffCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DiffCollection
     */
    private $helper;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->helper = new DiffCollection();
    }

    /**
     * New collection should contain missed in the first collection elements.
     * New artists should not be added.
     * @covers DiffCollection::calculateDiff()
     */
    public function testCollectionDifference()
    {
        $coll1 = new Collection();
        $artist = new Artist('artist');
        $coll1->addArtist($artist);
        $coll1->addAlbum($artist, new Album('album1'));
        $coll1->addAlbum($artist, new Album('album2'));

        $coll2 = new Collection();
        $artist = new Artist('artist');
        $coll2->addArtist($artist);
        $coll2->addArtist(new Artist('artist2'));
        $coll2->addAlbum($artist, new Album('album1'));
        $coll2->addAlbum($artist, new Album('album2'));
        $coll2->addAlbum($artist, new Album('album3'));

        $actualColl = $this->helper->calculateDiff($coll1, $coll2);

        $expectedColl = new Collection();
        $artist = new Artist('artist');
        $expectedColl->addArtist($artist);
        $expectedColl->addAlbum($artist, new Album('album3'));

        $this->assertEquals(
            $expectedColl->getStorage()->serialize(),
            $actualColl->getStorage()->serialize()
        );
    }
}
