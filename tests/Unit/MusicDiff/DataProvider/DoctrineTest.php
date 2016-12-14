<?php
namespace Tests\Unit\MusicDiff\DataProvider;

use AppBundle\Entity\Album;
use AppBundle\Entity\Artist;
use AppBundle\Entity\ArtistFetch;
use AppBundle\Repository\AlbumRepository;
use AppBundle\Repository\ArtistFetchRepository;
use AppBundle\Repository\ArtistRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use MusicDiff\Collection\Collection;
use MusicDiff\DataProvider\AbstractProvider;
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
     * No collection in case of invalid artist.
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
        $collection = $dataProvider->findByArtist('');

        $this->assertNull($collection);
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
     * Fetch artist in the next provider if last fetch date is greater then {seconds}.
     * @covers Doctrine::findByArtist()
     */
    public function testArtistInvalidation()
    {
        $artist = new Artist('test');

        $date = new \DateTime('now - 2 seconds');

        $artistRepo = $this->getMockBuilder(ArtistRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $artistRepo->expects($this->any())
            ->method('findArtistByName')
            ->willReturn($artist);

        $artistFetchRepo = $this->getMockBuilder(ArtistFetchRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $artistFetchRepo->expects($this->any())
            ->method('findOneBy')
            ->willReturn(new ArtistFetch($artist, $date));

        $doctrine = $this->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $manager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $manager->expects($this->any())
            ->method('getRepository')
            ->willReturnMap([
                ['AppBundle:Artist', $artistRepo],
                ['AppBundle:ArtistFetch', $artistFetchRepo]
            ]);

        $manager->expects($this->once())->method('flush');

        $doctrine->expects($this->any())
            ->method('getManager')
            ->willReturn($manager);

        $dataProviderAbstractMock = $this->getMockForAbstractClass(AbstractProvider::class);
        $dataProviderAbstractMock->expects($this->once())
            ->method('findByArtist')
            ->with('test')
            ->willReturn(new Collection());

        $dataProvider1 = new Doctrine($doctrine, 1);
        $dataProvider1->setNext($dataProviderAbstractMock);
        $dataProvider1->findByArtist('test');

        $dataProvider2 = new Doctrine($doctrine, 3);
        $dataProvider2->setNext($dataProviderAbstractMock);
        $dataProvider2->findByArtist('test');
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

        $artistRepo = $this->getMockBuilder(ArtistRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $artistRepo->expects($this->any())
            ->method('findArtistByName')
            ->willReturn(null);

        $albumRepo = $this->getMockBuilder(AlbumRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $albumRepo->expects($this->any())
            ->method('findAlbumByName')
            ->willReturn(null);

        $doctrine = $this->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $manager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $manager->expects($this->any())
            ->method('getRepository')
            ->willReturnMap([
                ['AppBundle:Artist', $artistRepo],
                ['AppBundle:Album', $albumRepo]
            ]);

        // 1 artist, 2 albums, 1 fetch time.
        $manager->expects($this->exactly(4))
            ->method('persist')
            ->withConsecutive($album, $album2, $artist);

        $manager->expects($this->once())
            ->method('flush');

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

        $dataProviderMock = $this->getMockForAbstractClass(AbstractProvider::class);
        $dataProviderMock->expects($this->once())->method('findByArtist')->with('test')->willReturn($collection);

        $dataProvider = new Doctrine($doctrine);
        $dataProvider->setNext($dataProviderMock);
        $dataProvider->findByArtist('test');
    }
}
