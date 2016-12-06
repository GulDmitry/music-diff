<?php
namespace Tests\Unit\MusicDiff;

use MusicDiff\Collection\Collection;
use MusicDiff\DataProvider\DataProviderInterface;
use MusicDiff\Entity\Album;
use MusicDiff\Entity\Artist;
use MusicDiff\MusicDiff;

class MusicDiffTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @inheritdoc
     */
    protected function setUp()
    {
    }

    /**
     * @expectedException \MusicDiff\Exception\InvalidArgumentException
     */
    public function testNoInitCollection()
    {
        $dataProvider = $this->createMock(DataProviderInterface::class);
        $musicDiff = new MusicDiff($dataProvider);

        $musicDiff->restoreCollection();
    }

    /**
     * Test that collection can be restored via data provider and difference can be received.
     */
    public function testBasicWorkflow()
    {
        $dataProvider = $this->createMock(DataProviderInterface::class);

        $returnColl1 = new Collection();
        $returnAlbum = new Album('Init Album 1');
        $returnAlbum->setLength(1);
        $returnArtist = new Artist('Init Artist 1');
        $returnColl1->addAlbum($returnArtist, $returnAlbum);
        $returnColl1->addAlbum($returnArtist, new Album('New Album 1'));

        $returnColl2 = new Collection();
        $returnColl1->addArtist(new Artist('New Artist 1'));

        $dataProvider->expects($this->exactly(2))->method('findByArtist')->willReturnOnConsecutiveCalls(
            $returnColl1,
            $returnColl2
        );

        $musicDiff = new MusicDiff($dataProvider);

        $initCollection = new Collection();
        $initCollection->addAlbum(new Artist('Init Artist 1'), new Album('Init Album 1'));
        $initCollection->addArtist(new Artist('Init Artist 2'));

        $musicDiff->setInitCollection($initCollection);
        $restoredCollection = $musicDiff->restoreCollection();

        $expectedCollection = new Collection();
        $expectedAlbum1 = new Album('Init Album 1');
        $expectedAlbum1->setLength(1);
        $expectedArtist = new Artist('Init Artist 1');
        $expectedCollection->addAlbum($expectedArtist, $expectedAlbum1);
        $expectedCollection->addAlbum($expectedArtist, new Album('New Album 1'));
        $expectedCollection->addArtist(new Artist('New Artist 1'));
        $expectedCollection->addArtist(new Artist('Init Artist 2'));

        $this->assertEquals(
            $expectedCollection->getStorage()->serialize(),
            $restoredCollection->getStorage()->serialize()
        );
    }
}
