<?php
namespace Tests\Unit\MusicDiff\DataProvider;

use MusicBrainz\Artist;
use MusicDiff\DataProvider\MusicBrainz as MusicBrainzDataProvider;
use MusicBrainz\MusicBrainz as MusicBrainzClient;
use MusicDiff\DataProvider\MusicBrainz;

class MusicBrainzTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MusicBrainzDataProvider
     */
    private $dataProvider;

    /**
     * @var MusicBrainzClient|\PHPUnit_Framework_MockObject_MockObject
     */
    private $musicBrainzClient;

    /**
     * Setup data provider and MusicBrainz client.
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->musicBrainzClient = $this->getMockBuilder(MusicBrainzClient::class)
            ->setMethods(['search', 'browseReleaseGroup', 'isValidMBID'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->musicBrainzClient->expects($this->any())->method('isValidMBID')->willReturn(true);
        $this->dataProvider = new MusicBrainzDataProvider($this->musicBrainzClient);
    }

    /**
     * Match artist with score 95+
     */
    public function testScope()
    {
        $artistData = [
            'id' => 'artistId',
            'score' => 94,
        ];
        $mbArtist = new Artist($artistData, $this->musicBrainzClient);

        $this->musicBrainzClient->expects($this->any())->method('search')->willReturn([$mbArtist]);

        $collection = $this->dataProvider->findByArtist('artist');
        $this->assertNull($collection);
    }

    /**
     * Find releases by artist name.
     */
    public function testFindByArtist()
    {
        $artistData = [
            'id' => 'artistId',
            'type' => 'artistType',
            'name' => 'artistName',
            'sort-name' => 'artistSort-name',
            'gender' => 'artistGender',
            'country' => 'artistCountry',
            'life-span' => [
                'begin' => '22-11-2000',
                'ended' => '2011-11-11',
            ],
            'score' => 100,
        ];
        $mbArtist = new Artist($artistData, $this->musicBrainzClient);
        $releases = [
            'release-groups' => [
                [
                    'title' => 'albumTitle1',
                    'primary-type' => 'albumType1',
                    'secondary-types' => ['stype11', 'stype21'],
                    'first-release-date' => '2001-05-10',
                ],
                [
                    'title' => 'albumTitle2',
                    'primary-type' => 'albumType2',
                    'secondary-types' => ['stype12', 'stype22'],
                    'first-release-date' => '2002-05-10',
                ],
            ]
        ];

        $this->musicBrainzClient->expects($this->once())->method('search')->willReturn([$mbArtist]);
        $this->musicBrainzClient->expects($this->once())->method('browseReleaseGroup')->willReturn($releases);

        $collection = $this->dataProvider->findByArtist('artist')->getStorage();

        $collection->rewind();
        $actualArtist = $collection->current();

        $actualAlbumCollection = $collection->getInfo();
        $actualAlbumCollection->rewind();
        $actualAlbum1 = $actualAlbumCollection->current();
        $actualAlbumCollection->next();
        $actualAlbum2 = $actualAlbumCollection->current();

        $this->assertEquals([
            'artistName',
            'artistCountry',
            '2000-11-22',
            '2011-11-11',
        ], [
            $actualArtist->getName(),
            $actualArtist->getCountry(),
            $actualArtist->getBeginDate(),
            $actualArtist->getEndDate(),
        ]);
        $this->assertEquals([
            'albumTitle1',
            '2001-05-10',
            ['albumtype1', 'stype11', 'stype21']
        ], [
            $actualAlbum1->getName(),
            $actualAlbum1->getReleaseDate(),
            $actualAlbum1->getTypes(),
        ]);
        $this->assertEquals([
            'albumTitle2',
            '2002-05-10',
            ['albumtype2', 'stype12', 'stype22']
        ], [
            $actualAlbum2->getName(),
            $actualAlbum2->getReleaseDate(),
            $actualAlbum2->getTypes(),
        ]);
    }
}
