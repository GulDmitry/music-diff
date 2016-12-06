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
            ->will($this->returnValue($repo));

        $doctrine->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($repo));

        $doctrine->expects($this->any())
            ->method('getManager')
            ->will($this->returnValue($manager));

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
     */
    public function testFindByArtist()
    {
        $date = new \DateTime('2000-10-10');
        $artist = (new Artist())->setName('Blind Guardian')->setCountry('DE')->setBeginDate($date);
        $album = (new Album($artist))->setName('Mirror Mirror');
        $album2 = (new Album($artist))->setName('Imagination')->setLength(6)->setDate($date);

        $artist->addAlbum($album);
        $artist->addAlbum($album2);

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
}
