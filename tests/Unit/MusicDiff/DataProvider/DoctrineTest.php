<?php
namespace Tests\Unit\MusicDiff\DataProvider;

use AppBundle\Entity\Album;
use AppBundle\Entity\Artist;
use AppBundle\Repository\ArtistRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use MusicDiff\Collection\Collection;
use MusicDiff\DataProvider\Doctrine;
use MusicDiff\Entity\Artist as MBArtist;
use MusicDiff\Entity\Album as MBAlbum;

class DoctrineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generate doctrine registry.
     * @param EntityRepository $repo
     * @return Registry|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getDoctrineMock(EntityRepository $repo)
    {
        $doctrine = $this->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $manager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $manager->expects($this->any())
            ->method('getRepository')
            ->willReturn($repo);

        $doctrine->expects($this->any())
            ->method('getRepository')
            ->willReturn($repo);

        $doctrine->expects($this->any())
            ->method('getManager')
            ->willReturn($manager);

        return $doctrine;
    }

    /**
     * @expectedException \MusicDiff\Exception\NotFoundException
     */
    public function testNoArtistInDB()
    {
        $repo = $this->getMockBuilder(ArtistRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repo->expects($this->any())
            ->method('findArtistByName')
            ->willReturn(null);

        $doctrine = $this->getDoctrineMock($repo);

        $dataProvider = new Doctrine($doctrine);
        $dataProvider->findByArtist('');
    }

    /**
     * Find releases by artist name.
     * @covers Doctrine::findByArtist()
     */
    public function testFindByArtist()
    {
        $date = new \DateTime('2000-10-10');

        $artist = $this->getMockBuilder(Artist::class)
            ->setConstructorArgs(['Blind Guardian'])
            ->getMock();

        $album = new Album('Mirror Mirror', $artist);
        $album2 = (new Album('Imagination', $artist))->setLength(6)->setDate($date);

        $artist->expects($this->any())->method('getName')->willReturn('Blind Guardian');
        $artist->expects($this->any())->method('getCountry')->willReturn('DE');
        $artist->expects($this->any())->method('getBeginDate')->willReturn($date);
        $artist->expects($this->any())->method('getAlbums')->willReturn([$album, $album2]);

        $repo = $this->getMockBuilder(ArtistRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repo->expects($this->any())
            ->method('findArtistByName')
            ->willReturn($artist);

        $doctrine = $this->getDoctrineMock($repo);

        $expectedCollection = new Collection();
        $expectedArtist = new MBArtist('Blind Guardian');
        $expectedArtist->setCountry('DE');
        $expectedArtist->setBeginDate('2000-10-10');
        $expectedCollection->addAlbum($expectedArtist, new MBAlbum('Mirror Mirror'));
        $expectedAlbum = new MBAlbum('Imagination');
        $expectedAlbum->setReleaseDate('2000-10-10');
        $expectedAlbum->setLength(6);
        $expectedCollection->addAlbum($expectedArtist, $expectedAlbum);

        $dataProvider = new Doctrine($doctrine);
        $actualCollection = $dataProvider->findByArtist('test');

        $this->assertEquals(
            $expectedCollection->getStorage()->serialize(),
            $actualCollection->getStorage()->serialize()
        );
    }

    /**
     * Convert collection to DB entities.
     * @covers Doctrine::saveCollectionToDB()
     */
    public function testSaveCollectionToDB()
    {
        $artist = $this->getMockBuilder(Artist::class)
            ->setConstructorArgs(['Blind Guardian'])
            ->getMock();

        $date = new \DateTime('2000-10-10');
        $album = new Album('Mirror Mirror', $artist);
        $album2 = (new Album('Imagination', $artist))->setLength(6)->setDate($date);

        $artist->expects($this->any())->method('getName')->willReturn('Blind Guardian');
        $artist->expects($this->any())->method('getCountry')->willReturn('DE');
        $artist->expects($this->any())->method('getBeginDate')->willReturn($date);
        $artist->expects($this->any())->method('getAlbums')->willReturn([$album, $album2]);

        $repo = $this->getMockBuilder(ArtistRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repo->expects($this->any())
            ->method('findArtistByName')
            ->willReturn(null);

        $doctrine = $this->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $manager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $manager->expects($this->any())
            ->method('getRepository')
            ->willReturn($repo);

        $manager->expects($this->exactly(3))
            ->method('persist')
            ->withConsecutive($album, $album2, $artist);

        $manager->expects($this->once())
            ->method('flush');

        $doctrine->expects($this->any())
            ->method('getRepository')
            ->willReturn($repo);

        $doctrine->expects($this->any())
            ->method('getManager')
            ->willReturn($manager);

        $collection = new Collection();
        $artist = new MBArtist('Blind Guardian');
        $artist->setCountry('DE');
        $artist->setBeginDate('2000-10-10');
        $collection->addAlbum($artist, new MBAlbum('Mirror Mirror'));
        $album = new MBAlbum('Imagination');
        $album->setReleaseDate('2000-10-10');
        $album->setLength(6);
        $collection->addAlbum($artist, $album);

        $dataProvider = new Doctrine($doctrine);
        $dataProvider->saveCollectionToDB($collection);
    }
}
