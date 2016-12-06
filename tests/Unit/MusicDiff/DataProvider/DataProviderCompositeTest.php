<?php
namespace Tests\Unit\MusicDiff\DataProvider;

use MusicDiff\Collection\Collection;
use MusicDiff\DataProvider\DataProviderComposite;
use MusicDiff\DataProvider\DataProviderInterface;
use MusicDiff\Entity\Album;
use MusicDiff\Entity\Artist;
use MusicDiff\Exception\InvalidArgumentException;

class DataProviderCompositeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * At leas one provider should be specified.
     * @expectedException InvalidArgumentException
     */
    public function testNoDataProviders()
    {
        new DataProviderComposite(true);
    }

    /**
     * Data is collected from all providers.
     */
    public function testCompositionWithMerge()
    {
        $artist1 = new Artist('Artist One');
        $artist1->setCountry('DE');
        $album1 = new Album('Album One');
        $album1->setLength(1);
        $artist4 = new Artist('Artist Four');
        $collection1 = new Collection();
        $collection1->addAlbum($artist1, $album1);
        $collection1->addArtist($artist4);

        $dataProvider1 = $this->createMock(DataProviderInterface::class);
        $dataProvider1->expects($this->once())->method('findByArtist')->willReturn($collection1);

        $artist2 = new Artist('Artist One');
        $album2 = new Album('Album Two');
        $artist3 = new Artist('Artist Two');
        $collection2 = new Collection();
        $collection2->addArtist($artist3);
        $collection2->addAlbum($artist2, $album2);

        $dataProvider2 = $this->createMock(DataProviderInterface::class);
        $dataProvider2->expects($this->once())->method('findByArtist')->willReturn($collection2);

        $expectedCollection = new Collection();
        $expectedCollection->addArtist($artist1);
        $expectedCollection->addAlbum($artist1, $album1);
        $expectedCollection->addAlbum($artist1, $album2);
        // New Artists in the end
        $expectedCollection->addArtist($artist3);
        $expectedCollection->addArtist($artist4);

        $composite = new DataProviderComposite(true, $dataProvider1, $dataProvider2);

        $actualStorage = $composite->findByArtist('test');

        $this->assertEquals(
            $expectedCollection->getStorage()->serialize(),
            $actualStorage->getStorage()->serialize()
        );
    }

    /**
     * Data is collected from the first provider only.
     */
    public function testCompositionNoMerge()
    {
        $artist1 = new Artist('Artist One');
        $artist1->setCountry('DE');
        $album1 = new Album('Album One');
        $album1->setLength(1);
        $artist4 = new Artist('Artist Four');
        $collection1 = new Collection();
        $collection1->addAlbum($artist1, $album1);
        $collection1->addArtist($artist4);

        $dataProvider1 = $this->createMock(DataProviderInterface::class);
        $dataProvider1->expects($this->once())->method('findByArtist')->willReturn($collection1);

        $collection2 = new Collection();
        $collection2->addArtist(new Artist('Artist Two'));

        $dataProvider2 = $this->createMock(DataProviderInterface::class);
        $dataProvider2->expects($this->never())->method('findByArtist')->willReturn($collection2);

        $expectedCollection = new Collection();
        $expectedCollection->addArtist($artist1);
        $expectedCollection->addAlbum($artist1, $album1);
        $expectedCollection->addArtist($artist4);

        $composite = new DataProviderComposite(false, $dataProvider1, $dataProvider2);

        $actualStorage = $composite->findByArtist('test');

        $this->assertEquals(
            $expectedCollection->getStorage()->serialize(),
            $actualStorage->getStorage()->serialize()
        );
    }
}
