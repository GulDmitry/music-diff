<?php
namespace Tests\Unit\MusicDiff\Collection\Converter;

use MusicDiff\Collection\Collection;
use MusicDiff\Collection\Converter\ArrayConverter;
use MusicDiff\Entity\Album;
use MusicDiff\Entity\Artist;

class ArrayConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     * @var ArrayConverter
     */
    private $converter;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->converter = new ArrayConverter();
        $this->collection = new Collection();
    }

    /**
     * Collection can be represented as array and restored.
     */
    public function testConverting()
    {
        $artist = new Artist('artist 1');
        $artist->setCountry('DE');
        $this->collection->addArtist($artist);
        $this->collection->addArtist(new Artist('artist 2'));

        $album = new Album('album');
        $album->setLength(6);
        $this->collection->addAlbum($artist, $album);

        $expectedArray = [
            [
                'name' => 'artist 1',
                'country' => 'DE',
                'beginDate' => null,
                'endDate' => null,
                'genres' => [],
                'albums' => [
                    [
                        'name' => 'album',
                        'length' => 6,
                        'releaseDate' => null,
                        'types' => [],
                    ],
                ],
            ],
            [
                'name' => 'artist 2',
                'albums' => [],
                'country' => null,
                'beginDate' => null,
                'endDate' => null,
                'genres' => [],
            ]
        ];

        $actualArray = $this->converter->fromCollection($this->collection);
        $actualCollection = $this->converter->toCollection($actualArray);

        $this->assertEquals($expectedArray, $actualArray);
        $this->assertEquals(
            $this->collection->getStorage()->serialize(),
            $actualCollection->getStorage()->serialize()
        );
    }
}
